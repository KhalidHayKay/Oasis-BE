<?php

namespace App\DTOs;

use App\Models\CheckoutSession;

class PaymentInitResult
{
    public function __construct(
        public readonly CheckoutSession $session,
        public readonly string $clientSecret,
        public readonly string $reference,
    ) {}
}
