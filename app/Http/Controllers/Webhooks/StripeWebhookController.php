<?php

namespace App\Http\Controllers\Webhooks;

use Stripe\Webhook;
use App\Models\Payment;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig,
                config('services.stripe.secret')
            );
        } catch (\Throwable $e) {
            return response('Invalid signature', 400);
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

            $payment = Payment::where('reference', $intent->id)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                return; // Unknown payment, ignore safely
            }

            if ($payment->status === 'successful') {
                return; // Idempotent: already processed
            }

            // Amount verification (MANDATORY)
            if ($payment->amount !== $intent->amount_received) {
                throw new \RuntimeException('Payment amount mismatch');
            }

            $payment->update([
                'status'       => 'successful',
                'raw_response' => $intent->toArray(),
            ]);

            $order = $payment->order()->lockForUpdate()->first();

            if ($order->status !== 'paid') {
                $order->update([
                    'status' => 'paid',
                ]);
            }

            // Post-payment side effects
            // Inventory::deduct($order)
            // Cart::clear($order->user_id)
            // Dispatch OrderPaid job
        });
    }

    protected function handleFailure(PaymentIntent $intent)
    {
        DB::transaction(function () use ($intent) {

            $payment = Payment::where('reference', $intent->id)
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
