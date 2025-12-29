<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;

class OrderService
{
    public function make(array $data, Cart $cart, User $user)
    {
        DB::beginTransaction();
        try {
        $shippingAddress = Address::create([
            'user_id'    => $user->id,
            'name'       => $data['shipping_name'],
            'phone'      => $data['shipping_phone'],
            'address'    => $data['shipping_address'],
            'city'       => $data['shipping_city'],
            'state'      => $data['shipping_state'],
            'lga'        => $data['shipping_lga'],
            'type'       => 'shipping',
            'is_default' => $data['is_default'] ?? false,
        ]);

        $order = Order::create([
            'user_id'          => $user->id,
            'order_number'     => $this->generateOrderNumber(),
            'customer_email'   => $data['customer_email'],
            'address_id' => $shippingAddress->id,

            'subtotal'         => $cart->total_price,
            'tax'              => 0,
            'shipping_fee'     => 0,
            'total'            => $cart->total_price,
            'status'           => 'pending',
        ]);

        foreach ($cart->products as $product) {
            OrderItem::create([
                'order_id'            => $order->id,
                'product_id'          => $product->id,
                'product_name'        => $product->name,
                'product_description' => $product->description ?? null,
                'price'               => $product->pivot->price,
                'quantity'            => $product->pivot->quantity,
                'subtotal'            => $product->pivot->price * $product->pivot->quantity,
            ]);
        }

        return [
            'order'            => $order->load('items'),
            'shipping_address' => $shippingAddress,
        ];} catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
    }

    private function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . str_pad(
            Order::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}
