<?php

namespace App\Contracts;

use App\Models\Payment;
use App\DTOs\PaymentIntentData;
use App\Models\CheckoutSession;

interface PaymentGatewayInterface
{
    public function getName(): string;

    public function initializePayment(CheckoutSession $checkoutSession, Payment $payment): PaymentIntentData;

    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntentData;
}
