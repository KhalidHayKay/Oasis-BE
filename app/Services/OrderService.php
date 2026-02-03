<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\CheckoutSession;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Support\Calculator;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(protected readonly Calculator $calculator) {}

    public function makeFromPayment(Payment $payment)
    {
        $checkoutSession = CheckoutSession::where('id', $payment->checkout_session_id)->firstOrfail();

        $order = $payment->order()->create([
            'user_id'          => $checkoutSession->user_id,
            'order_number'     => $this->generateOrderNumber(),

            'customer_email'   => $checkoutSession->customer_email,
            'shipping_address' => $checkoutSession->shipping_address,
            // todo: 'get billing address from payment method somehow'
            'billing_address'  => $checkoutSession->shipping_address,

            'subtotal'         => $checkoutSession->subtotal,
            'tax'              => $checkoutSession->tax,
            'shipping_fee'     => $checkoutSession->shipping_fee,
            'total'            => $checkoutSession->total,
            'currency'         => $checkoutSession->currency,
            'status'           => 'confirmed',
        ]);

        foreach ($checkoutSession->checkoutItems as $item) {
            $order->items()->create([
                'order_id'               => $order->id,
                'product_id'             => $item->product_id,
                'product_name'           => $item->product_name,
                'product_selected_color' => $item->product_selected_color,
                'product_description'    => $item->product_description,
                'quantity'               => $item->quantity,
            ]);
        }

        return $order;
    }

    public function makeFromCart(array $data, User $user): array
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

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'order_id'               => $order->id,
                    'product_id'             => $item->product_id,
                    'product_name'           => $item->product_name,
                    'product_selected_color' => $item->color,
                    'product_description'    => $item->product_description ?? null,
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
