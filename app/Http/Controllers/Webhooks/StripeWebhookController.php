<?php

namespace App\Http\Controllers\Webhooks;

use Stripe\Webhook;
use App\Models\Payment;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\CheckoutService;

class StripeWebhookController extends Controller
{
    public function __construct(
        protected readonly OrderService $orderService,
        protected readonly CheckoutService $checkoutService,
    ) {}

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig,
                config('services.stripe.webhook_secret')
            );
        } catch (\Throwable $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Invalid signature',
            ], 400);
        }

        match ($event->type) {
            'payment_intent.succeeded'      =>
            $this->handleSuccess($event->data->object),

            'payment_intent.payment_failed' =>
            $this->handleFailure($event->data->object),

            default                         => null,
        };

        return response()->json(['received' => true]);
    }

    protected function handleSuccess(PaymentIntent $intent)
    {
        try {
            DB::transaction(function () use ($intent) {

                $payment = Payment::where('transaction_reference', $intent->id)
                    ->lockForUpdate()
                    ->first();

                $checkoutSession = $payment?->checkoutSession;

                if (! $payment) {
                    Log::warning('Payment not found for intent: ' . $intent->id);
                    return;
                }

                if ($payment->status === 'successful') {
                    Log::info('Payment already processed: ' . $payment->id);
                    return;
                }

                if (! $checkoutSession->hasItemsCaptured()) {
                    $this->checkoutService->captureCheckoutItems($checkoutSession);
                }

                $expectedAmountInCents = (int) round($payment->amount * 100);

                if ($expectedAmountInCents !== $intent->amount_received) {
                    $payment->update([
                        'status'         => 'failed',
                        'raw_response'   => $intent->toArray(),
                        'failure_reason' => 'Amount mismatch: expected ' . $expectedAmountInCents . ', received ' . $intent->amount_received,
                    ]);

                    $checkoutSession->update(['status' => 'requires_attention']);
                    $checkoutSession->cart->items()->delete();

                    Log::error('Payment amount mismatch', [
                        'payment_id' => $payment->id,
                        'expected'   => $expectedAmountInCents,
                        'received'   => $intent->amount_received,
                    ]);

                    return;
                }

                $payment->update([
                    'status'       => 'successful',
                    'raw_response' => $intent->toArray(),
                    'paid_at'      => now(),
                ]);

                $order = $this->orderService->makeFromPayment($payment);

                $this->checkoutService->completeCheckout($checkoutSession);

                Log::info('Order #' . $order->id . ' created for Payment #' . $payment->id);
            });
        } catch (\Exception $e) {
            Log::error('Error processing payment webhook', [
                'intent_id' => $intent->id,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            $payment = Payment::where('transaction_reference', $intent->id)->first();
            if ($payment && $payment->status !== 'successful') {
                $payment->update([
                    'status'         => 'failed',
                    'failure_reason' => 'Order creation failed: ' . $e->getMessage(),
                ]);
            }

            $checkoutSession = $payment->checkoutSession;
            $checkoutSession->update(['status' => 'requires_attention']);
            $checkoutSession->cart->items()->delete();
        }
    }

    protected function handleFailure(PaymentIntent $intent)
    {
        DB::transaction(function () use ($intent) {

            $payment = Payment::where('transaction_reference', $intent->id)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                Log::warning('Payment not found for failed intent: ' . $intent->id);
                return;
            }

            if ($payment->status === 'successful') {
                Log::warning('Received failure for already successful payment: ' . $payment->id);
                return;
            }

            $payment->update([
                'status'         => 'failed',
                'raw_response'   => $intent->toArray(),
                'failure_reason' => $intent->last_payment_error?->message ?? 'Payment failed',
            ]);
        });
    }
}
