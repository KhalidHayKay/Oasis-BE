<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Stripe\StripeClient;
use App\DTOs\GatewayInitResult;
use App\Contracts\PaymentGatewayInterface;

class StripeGateway implements PaymentGatewayInterface
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function getName(): string
    {
        return 'stripe';
    }

    public function initializePayment(Order $order, Payment $payment): GatewayInitResult
    {
        $intent = $this->stripe->paymentIntents->create([
            'amount'   => $payment->amount * 100, // amount in cents
            'currency' => 'usd',
            'metadata' => [
                'order_id'   => $order->id,
                'payment_id' => $payment->id,
            ],
        ]);

        $payment->update([
            'reference'        => $intent->id,
            'gateway_response' => $intent->toArray(),
        ]);

        return new GatewayInitResult(
            clientSecret: $intent->client_secret,
            reference: $intent->id
        );
    }
}
