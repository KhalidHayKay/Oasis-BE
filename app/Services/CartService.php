<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;

class CartService
{
    public function show(User $user)
    {
        $products = $user->cart->products;

        return $products;
    }

    public function addItem(array $data, User $user)
    {
        $product = Product::findOrFail($data['product_id']);

        $cartProduct = $this->getCartProduct($user, $product);

        if ($cartProduct) {
            return $this->incrementQuantity($user, $product, $data['quantity']);
        }

        $user->cart->products()->attach($product->id, [
            'quantity' => $data['quantity'],
            // 'price'    => $product->price,
        ]);

        return $user->cart->fresh('products');
    }

    public function incrementQuantity(User $user, Product $product, int $by = 1)
    {
        $cartProduct = $this->getCartProduct($user, $product);

        if (! $cartProduct) {
            throw new \RuntimeException('Product not in cart.');
        }

        $user->cart->products()->updateExistingPivot(
            $product->id,
            [
                'quantity' => $cartProduct->pivot->quantity + $by,
            ]
        );

        return $user->cart->fresh('products');
    }

    public function decrementQuantity(User $user, Product $product, int $by = 1)
    {
        $cartProduct = $this->getCartProduct($user, $product);

        if (! $cartProduct) {
            throw new \RuntimeException('Product not in cart.');
        }

        $newQuantity = $cartProduct->pivot->quantity - $by;

        if ($newQuantity <= 0) {
            return $this->removeItem($user, $product);
        }

        $user->cart->products()->updateExistingPivot(
            $product->id,
            ['quantity' => $newQuantity]
        );

        return $user->cart->fresh('products');
    }

    public function removeItem(User $user, Product $product)
    {
        $user->cart->products()->detach($product->id);

        return $user->cart->fresh('products');
    }

    public function clear(User $user)
    {
        $user->cart->products()->detach();

        return $user->cart->fresh('products');
    }

    /**
     * Get a product from the user's cart
     */
    protected function getCartProduct(User $user, Product $product)
    {
        return $user->cart
            ->products()
            ->where('product_id', $product->id)
            ->first();
    }
}
