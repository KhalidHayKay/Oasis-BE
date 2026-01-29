<?php

namespace App\Contracts;

use App\Models\Payment;
use App\DTOs\GatewayIntentData;
use App\Models\CheckoutSession;

interface PaymentGatewayInterface
{
    public function getName(): string;

    public function initializePayment(CheckoutSession $checkoutSession, Payment $payment): GatewayIntentData;

    public function retrievePaymentIntent(string $paymentIntentId): GatewayIntentData;
}
