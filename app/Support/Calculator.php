<?php

namespace App\Support;

use App\Models\Cart;
use App\DTOs\CheckoutTotals;

class Calculator
{
    public function checkoutFromCart(Cart $cart, array $shippingAddress): CheckoutTotals
    {
        $subtotal = 0;

        foreach ($cart->products as $product) {
            $subtotal += $this->priceWithDiscount($product->price) * $product->pivot->quantity;
        }

        $shipping = $this->calculateShipping($shippingAddress, $cart);
        $tax      = $this->calculateTax($subtotal, $shippingAddress);

        return new CheckoutTotals(
            subtotal: $subtotal,
            tax: $tax,
            shipping: $shipping,
            total: $subtotal + $tax + $shipping,
        );
    }

    public function priceWithDiscount(array $priceArr): int
    {
        $price      = $priceArr['amount'];
        $percentage = $priceArr['percentage'] ?? 0;

        if (! $percentage || $percentage <= 0) {
            return $price;
        }

        $discountAmount = round($price * ($percentage / 100));

        return max(0, $price - $discountAmount);
    }

    protected function calculateShipping(array $address, Cart $cart): int
    {
        // dummy logic
        if ($address['state'] === 'Lagos') {
            return 1500;
        }

        return 3000;
    }

    protected function calculateTax(int $subtotal, array $address): int
    {
        // dummy logic
        return (int) round($subtotal * 0.075);
    }
}
