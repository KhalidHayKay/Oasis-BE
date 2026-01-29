<?php

namespace App\Services;

use App\Models\Payment;
use Stripe\StripeClient;
use App\DTOs\GatewayIntentData;
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

    public function initializePayment(CheckoutSession $checkoutSession, Payment $payment): GatewayIntentData
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

        return new GatewayIntentData(
            clientSecret: $intent->client_secret,
            reference: $intent->id,
            additionalData: $intent->toArray()
        );
    }

    public function retrievePaymentIntent(string $paymentIntentId): GatewayIntentData
    {
        $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);

        return new GatewayIntentData(
            clientSecret: $paymentIntent->client_secret,
            reference: $paymentIntent->id,
            status: $paymentIntent->status,
        );
    }
}
