<?php

namespace App\Contracts;

use App\Models\Payment;
use App\DTOs\GatewayInitResult;
use App\Models\CheckoutSession;

interface PaymentGatewayInterface
{
    public function getName(): string;

    public function initializePayment(CheckoutSession $checkoutSession, Payment $payment): GatewayInitResult;

    public function retrievePaymentIntent(string $paymentIntentId): object;
}
