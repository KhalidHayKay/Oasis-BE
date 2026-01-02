<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\CheckoutSession;

class CheckoutService
{
    private const string EMPTY_CART    = 'empty_cart';
    private const string CART_CONFLICT = 'cart_conflict';

    private const string UNAVAILABLE_PRODUCT = 'unavailable_product';
    private const string INSUFFICIENT_STOCK  = 'insufficient_stock';

    public function validateCartForCheckout(Cart $cart, User $user): array
    {
        if ($cart->products->isEmpty()) {
            return [
                'ok'     => false,
                'reason' => self::EMPTY_CART,
                'issues' => [],
            ];
        }

        $issues = [];

        foreach ($cart->products as $product) {
            if (! $product->is_available) {
                $issues[] = [
                    'product_id' => $product->id,
                    'type'       => self::UNAVAILABLE_PRODUCT,
                    'message'    => "{$product->name} is no longer available",
                ];
                continue;
            }

            if ($product->pivot->quantity > $product->stock) {
                $issues[] = [
                    'product_id' => $product->id,
                    'type'       => self::INSUFFICIENT_STOCK,
                    'available'  => $product->stock,
                    'requested'  => $product->pivot->quantity,
                ];
            }
        }

        if (! empty($issues)) {
            return [
                'ok'     => false,
                'reason' => self::CART_CONFLICT,
                'issues' => $issues,
            ];
        }

        $checkoutSession = CheckoutSession::where('cart_id', $cart->id)
            ->where('status', 'active')->first();

        if (! $checkoutSession) {
            $checkoutSession = CheckoutSession::create([
                'public_token' => Str::uuid(),
                'cart_id'      => $cart->id,
                'user_id'      => $user->id,
                'expires_at'   => now()->addMinutes(15),
            ]);
        }

        return [
            'ok'                  => true,
            'checkout_session_id' => $checkoutSession,
            'saved_addresses'     => $user?->addresses,
        ];
    }

    public function attachCustomerAndAddress(User $user, array $data): CheckoutSession
    {
        $checkoutSession = CheckoutSession::where('public_token', $data['checkout_token'])
            ->firstOrFail();

        if ($checkoutSession->status !== 'active') {
            throw new \RuntimeException('Checkout session is not active.');
        }

        $checkoutSession->customer_email = $user
            ? $user->email
            : $data['customer_email'];

        $checkoutSession->user_id = $user?->id;

        $checkoutSession->shipping_address = [
            'name'    => $data['shipping_name'],
            'phone'   => $data['shipping_phone'],
            'address' => $data['shipping_address'],
            'city'    => $data['shipping_city'],
            'state'   => $data['shipping_state'],
            'lga'     => $data['shipping_lga'],
        ];

        $checkoutSession->save();

        return $checkoutSession;
    }
}
