<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payment;
use App\Models\CheckoutSession;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentGatewayInterface;
use App\DTOs\PaymentIntentData;

class PaymentService
{
    public function __construct(private readonly PaymentGatewayInterface $gateway) {}

    public function initialize(User $user, array $data): PaymentIntentData
    {
        $checkoutSession = CheckoutSession::where('public_token', $data['checkout_token'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($checkoutSession->status !== 'active') {
            throw new \RuntimeException('Checkout session is no longer valid.');
        }

        $payment = $checkoutSession->payment;

        if ($payment && $payment->status === 'pending') {
            $result = $this->gateway->retrievePaymentIntent(
                $payment->transaction_reference
            );

            // If payment already succeeded, prevent re-payment
            if ($result->status === 'succeeded') {
                throw new \RuntimeException('Payment already completed for this checkout.');
            }

            return new PaymentIntentData(
                $result->clientSecret,
                $result->reference
            );

        }

        DB::beginTransaction();

        try {
            $payment = Payment::create([
                'user_id'             => $user->id,
                'checkout_session_id' => $checkoutSession->id,
                'payment_gateway'     => $this->gateway->getName(),
                'amount'              => $checkoutSession->total,
                'currency'            => $checkoutSession->currency,
                'status'              => 'pending',
            ]);

            $result = $this->gateway->initializePayment($checkoutSession, $payment);

            $payment->update([
                'transaction_reference' => $result->reference,
                'gateway_response'      => $result->additionalData,
            ]);

            $checkoutSession->update([
                'current_step' => 'payment',
            ]);

            DB::commit();

            return new PaymentIntentData(
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
        $payment = $this->getByReference($reference)
            ->where('user_id', $user->id)->first();

        if (! $payment) {
            throw new \InvalidArgumentException('Payment not found.');
        }

        $response = [
            'status'  => $payment->status,
            'orderId' => $payment->order?->id,
        ];

        switch ($payment->status) {
            case 'successful':
                if (! $payment->order) {
                    // Payment successful but order not created yet - still processing
                    $response['message'] = 'Payment confirmed, creating your order...';
                    $response['status']  = 'processing';
                } else {
                    $response['message'] = 'Payment completed successfully.';
                }
                break;

            case 'failed':
                $response['message'] = 'Payment failed.';
                $response['error'] = $payment->failure_reason ?? 'An error occurred during payment processing.';
                break;

            case 'pending':
                $response['message'] = 'Payment is still being processed.';
                break;

            case 'requires_attention':
                $response['message'] = 'Payment requires attention. Please contact support.';
                break;

            default:
                $response['message'] = 'Payment status unknown.';
        }

        return $response;
    }

    public function getByReference(string $reference): ?Payment
    {
        $payment = Payment::where('transaction_reference', $reference)->first();

        return $payment;
    }

    public function getIntentBySession(string $sessionId)
    {
        $payment = Payment::where('checkout_session_id', $sessionId)->first();

        if ($payment->status !== 'pending') {
            throw new \RuntimeException('No pending payment found for this session.');
        }

        $intent = $this->gateway->retrievePaymentIntent($payment->transaction_reference);

        return $intent;
    }
}
