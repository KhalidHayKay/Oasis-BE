<?php

namespace App\Services\Support;

class CheckoutTotals
{
    public function __construct(
        public int $subtotal,
        public int $tax,
        public int $shipping,
        public int $total,
    ) {}
}
