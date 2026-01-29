<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\CheckoutSession;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentGatewayInterface;
use App\DTOs\PaymentInitResult;

class PaymentService
{
    public function __construct(private readonly PaymentGatewayInterface $gateway) {}

    public function initialize(User $user, array $data): PaymentInitResult
    {
        $checkoutSession = CheckoutSession::where('public_token', $data['checkout_token'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($checkoutSession->status !== 'active') {
            throw new \RuntimeException('Checkout session is no longer valid.');
        }

        if ($checkoutSession->stripe_payment_intent_id) {
            $result = $this->gateway->retrievePaymentIntent(
                $checkoutSession->stripe_payment_intent_id
            );

            // If payment already succeeded, prevent re-payment
            if ($result->status === 'succeeded') {
                throw new \RuntimeException('Payment already completed for this checkout.');
            }

            return new PaymentInitResult(
                $checkoutSession,
                $result->clientSecret,
                $result->reference
            );

        }

        DB::beginTransaction();

        try {
            $payment = Payment::create([
                'checkout_session_id' => $checkoutSession->id,
                'payment_gateway'     => $this->gateway->getName(),
                'amount'              => $checkoutSession->total,
                'currency'            => $checkoutSession->currency,
                'status'              => 'initialized',
            ]);

            $result = $this->gateway->initializePayment($checkoutSession, $payment);

            $payment->update([
                'transaction_reference' => $result->reference,
                'gateway_response'      => $result->additionalData,
            ]);

            $checkoutSession->update([
                'stripe_payment_intent_id' => $result->reference,
                'current_step'             => 'payment',
            ]);

            DB::commit();

            return new PaymentInitResult(
                $checkoutSession,
                $result->clientSecret,
                $result->reference
            );

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirm(User $user, string $reference)
    {
        $payment = $this->getPaymentByReference($reference)
            ->where('user_id', $user->id)->first();

        if (! $payment) {
            throw new \InvalidArgumentException('Payment not found.');
        }

        return [
            'status'  => $payment->status,
            'orderId' => $payment->order->id,
        ];
    }

    public function getPaymentByReference(string $reference): ?Payment
    {
        $payment = Payment::where('transaction_reference', $reference)->first();

        return $payment;
    }
}
