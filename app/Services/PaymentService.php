<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\CheckoutSession;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentGatewayInterface;

class PaymentService
{
    public function __construct(private readonly PaymentGatewayInterface $gateway) {}

    public function initialize(User $user, array $data)
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

            return [
                'session'       => $checkoutSession,
                'client_secret' => $result->clientSecret,
                'reference'     => $result->reference,
            ];
        }

        DB::beginTransaction();

        try {
            $payment = Payment::create([
                'checkout_session_id'   => $checkoutSession->id,
                'order_id'              => null, // ← No order yet!
                'payment_gateway'       => $this->gateway->getName(),
                'amount'                => $checkoutSession->total,
                'currency'              => $checkoutSession->currency ?? 'USD',
                'status'                => 'initialized',

                'transaction_reference' => 'sdsdsdd',
            ]);

            $result = $this->gateway->initializePayment($checkoutSession, $payment);

            $checkoutSession->update([
                'stripe_payment_intent_id' => $result->reference,
                'current_step'             => 'payment',
            ]);

            DB::commit();

            return [
                'session'       => $checkoutSession,
                'client_secret' => $result->clientSecret,
                'reference'     => $result->reference,
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
