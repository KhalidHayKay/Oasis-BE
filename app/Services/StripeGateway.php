<?php

namespace App\Services;

use App\Models\Payment;
use Stripe\StripeClient;
use App\DTOs\PaymentIntentData;
use App\Contracts\PaymentGatewayInterface;
use App\Models\CheckoutSession;

class StripeGateway implements PaymentGatewayInterface
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.api_secret'));
    }

    public function getName(): string
    {
        return 'stripe';
    }

    public function initializePayment(CheckoutSession $checkoutSession, Payment $payment): PaymentIntentData
    {
        $intent = $this->stripe->paymentIntents->create([
            'amount'               => $payment->amount * 100, // amount in cents
            'currency'             => 'usd',
            'metadata'             => [
                'checkout_session_id' => $checkoutSession->id,
                'payment_id'          => $payment->id,
                'user_id'             => $checkoutSession->user_id,
            ],
            'payment_method_types' => ['card'],
        ]);

        return new PaymentIntentData(
            clientSecret: $intent->client_secret,
            reference: $intent->id,
            additionalData: $intent->toArray()
        );
    }

    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntentData
    {
        $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);

        return new PaymentIntentData(
            clientSecret: $paymentIntent->client_secret,
            reference: $paymentIntent->id,
            status: $paymentIntent->status,
        );
    }
}
