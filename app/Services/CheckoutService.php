<?php

namespace App\Services;

use App\Exceptions\CartValidationException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\CheckoutSession;
use App\Support\Calculator;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    private const string EMPTY_CART    = 'empty_cart';
    private const string CART_CONFLICT = 'cart_conflict';

    private const string UNAVAILABLE_PRODUCT = 'unavailable_product';
    private const string INSUFFICIENT_STOCK  = 'insufficient_stock';

    public function __construct(protected readonly Calculator $calculator) {}

    public function get(User $user)
    {
        $session = CheckoutSession::where('user_id', $user->id)
            ->where('status', 'active')->first();

        if (! $session) {
            return null;
        }

        if ($session->expires_at->isPast()) {
            $this->expire($session);
            return null;
        }

        return $session;
    }

    public function validateCartForCheckout(User $user): CheckoutSession
    {
        $cart = $user->cart;

        $checkoutSession = $this->get($user);

        if ($checkoutSession) {
            return $checkoutSession;
        }

        if ($cart->items->isEmpty()) {
            throw new CartValidationException(self::EMPTY_CART);
        }

        $issues = [];

        foreach ($cart->items as $item) {
            if (! $item->product->is_available) {
                $issues[] = [
                    'product_id' => $item->product_id,
                    'type'       => self::UNAVAILABLE_PRODUCT,
                    'message'    => "{$item->product_name} is no longer available",
                ];
                continue;
            }

            if ($item->quantity > $item->product->stock) {
                $issues[] = [
                    'product_id' => $item->product_id,
                    'type'       => self::INSUFFICIENT_STOCK,
                    'available'  => $item->product->stock,
                    'requested'  => $item->quantity,
                ];
            }
        }

        if (! empty($issues)) {
            throw new CartValidationException(self::CART_CONFLICT, $issues);
        }

        $session = CheckoutSession::create([
            'public_token' => Str::uuid(),
            'cart_id'      => $cart->id,
            'user_id'      => $user->id,
            'expires_at'   => now()->addMinutes(15),
            'current_step' => 'address',
        ]);

        return $session;
    }

    public function address(User $user, array $data): CheckoutSession
    {
        $checkoutSession = CheckoutSession::where('public_token', $data['checkout_token'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $checkoutSession->update([
            'customer_email'   => $user->email,
            'shipping_address' => [
                'fname'   => $data['fname'],
                'lname'   => $data['lname'],
                'phone'   => $data['phone'],
                'address' => $data['address'],
                'country' => $data['country'],
                'city'    => $data['city'],
            ],
        ]);

        $totals = $this->calculator->checkoutFromCart($user->cart, $checkoutSession->shipping_address);

        $checkoutSession->update([
            'subtotal'     => $totals->subtotal,
            'tax'          => $totals->tax,
            'shipping_fee' => $totals->shipping,
            'total'        => $totals->total,

            'current_step' => 'summary',
        ]);

        return $checkoutSession->load(['cart', 'cart.items']);
    }

    protected function expire(CheckoutSession $session): void
    {
        DB::transaction(function () use ($session) {
            $session->update(['status' => 'expired']);

            // future-safe hooks:
            // $session->cart->releaseInventory();
            // event(new CheckoutExpired($session));
        });
    }

    protected function estimateCartTotal(Cart $cart)
    {
        $total = 0;

        foreach ($cart->items as $item) {
            $total += ($item->unit_price * $item->quantity);
        }

        $cart->update(['total_price' => $total]);
    }
}
