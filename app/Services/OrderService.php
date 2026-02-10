<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\CheckoutSession;
use App\Models\Payment;
use App\Support\Calculator;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(protected readonly Calculator $calculator) {}

    public function getAll(User $user)
    {
        $orders = $user->orders()
            ->with([
                'items' => function ($query) {
                    $query->select('id', 'order_id', 'product_id') // Include foreign keys!
                        ->with('product.featuredImage');
                }
            ])
            ->get();

        return $orders;
    }

    public function get(Order $order)
    {
        $result = $order->load(['items', 'items.product', 'payment']);

        return $result;
    }

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
                'price_at_checkout'      => $item->price_at_checkout,
                'quantity'               => $item->quantity,
            ]);
        }

        return $order;
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
