<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\CheckoutSession;
use App\Models\OrderItem;
use App\Support\Calculator;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(protected readonly Calculator $calculator) {}

    public function make(array $data, User $user): array
    {
        $cart = $user->cart;

        DB::beginTransaction();

        $checkoutSession = CheckoutSession::where('public_token', $data['checkout_token'])
            ->lockForUpdate()
            ->firstOrFail();

        if ($checkoutSession->status !== 'active') {
            throw new \RuntimeException('Checkout session is no longer valid.');
        }

        if (! $checkoutSession->customer_email) {
            throw new \InvalidArgumentException('Customer has not been attached to checkout session.');
        }

        try {

            $billingIsShipping = $data['is_same_as_shipping'] ?? false;

            $shippingAddress = $cart->checkoutSession->shipping_address;
            $billingAddress  = $billingIsShipping
                ? $shippingAddress
                : [
                    'name'    => $data['billing_name'],
                    'phone'   => $data['billing_phone'],
                    'address' => $data['billing_address'],
                    'city'    => $data['billing_city'],
                    'state'   => $data['billing_state'],
                    'lga'     => $data['billing_lga'],
                ];

            $totals = $this->calculator->checkoutFromCart($cart, $shippingAddress);

            $order = Order::create([
                'checkout_session_id' => $checkoutSession->id,
                'user_id'             => $user->id,
                'order_number'        => $this->generateOrderNumber(),
                'customer_email'      => $user ? $user->email : $data['customer_email'],

                'shipping_address'    => $shippingAddress,
                'billing_address'     => $billingAddress,

                'subtotal'            => $totals->subtotal,
                'tax'                 => $totals->tax,
                'shipping_fee'        => $totals->shipping,
                'total'               => $totals->total,
                'status'              => 'pending',
            ]);

            foreach ($cart->products as $product) {
                OrderItem::create([
                    'order_id'            => $order->id,
                    'product_id'          => $product->id,
                    'product_name'        => $product->name,
                    'product_description' => $product->description ?? null,
                    'price'               => $this->calculator->priceWithDiscount($product->price),
                    'quantity'            => $product->pivot->quantity,
                    'subtotal'            => (int) $product->price * $product->pivot->quantity,
                ]);
            }

            $checkoutSession->update([
                'status' => 'converted',
            ]);

            DB::commit();

            return [
                'order'            => $order->load('items'),
                'shipping_address' => $shippingAddress,
            ];
        } catch (\Exception $e) {
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
