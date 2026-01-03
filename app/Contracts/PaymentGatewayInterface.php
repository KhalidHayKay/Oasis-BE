<?php

namespace App\Contracts;

use App\Models\Order;
use App\DTOs\GatewayInitResult;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function getName(): string;

    public function initializePayment(Order $order, Payment $payment): GatewayInitResult;
}
