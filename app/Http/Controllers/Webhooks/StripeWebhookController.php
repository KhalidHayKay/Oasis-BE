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

class StripeWebhookController extends Controller
{
    public function __construct(protected readonly OrderService $orderService) {}

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
        DB::transaction(function () use ($intent) {

            $payment = Payment::where('transaction_reference', $intent->id)
                ->lockForUpdate()
                ->first();

            // Unknown payment or already processed
            if (! $payment || $payment->status === 'successful') {
                return;
            }

            // Amount verification
            if ($payment->amount !== $intent->amount_received) {
                $payment->update(['status' => 'failed']);
                throw new \RuntimeException('Payment amount mismatch');
            }

            $payment->update([
                'status'       => 'successful',
                'raw_response' => $intent->toArray(),
                'paid_at'      => now(),
            ]);

            $order = $this->orderService->makeFromPayment($payment);

            Log::info('Order #' . $order->id . ' created for Payment #' . $payment->id);
        });
    }

    protected function handleFailure(PaymentIntent $intent)
    {
        DB::transaction(function () use ($intent) {

            $payment = Payment::where('transaction_reference', $intent->id)
                ->lockForUpdate()
                ->first();

            if (! $payment || $payment->status === 'successful') {
                return;
            }

            $payment->update([
                'status'       => 'failed',
                'raw_response' => $intent->toArray(),
            ]);
        });
    }

}
