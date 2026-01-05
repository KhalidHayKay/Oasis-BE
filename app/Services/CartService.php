<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\User;
use App\Models\Product;
use App\Support\Calculator;

class CartService
{
    public function __construct(private readonly Calculator $calculator) {}

    public function show(User $user)
    {
        $items = $user->cart->items;

        return $items;
    }

    public function addItem(array $data, User $user)
    {
        $product = Product::findOrFail($data['product_id']);

        $prevItem = $this->getCartItem($user, $product->id, $data['color']);

        if ($prevItem) {
            return $this->incrementQuantity($prevItem, $data['quantity']);
        }

        if (! in_array($data['color'], $product->colors)) {
            throw new \InvalidArgumentException('Selected color is not available for this product.');
        }

        $price = $this->calculator->priceWithDiscount($product->price);

        $item = $user->cart->items()->create([
            'product_id'          => $product->id,
            'product_name'        => $product->name,
            'product_image_id'    => $product->featuredImage->id,
            'product_description' => $product->description,
            'color'               => $data['color'],
            'unit_price'          => $price,
            'quantity'            => $data['quantity'],
            'subtotal'            => (int) $price * (int) $data['quantity'],
        ]);

        return $item;
    }

    public function incrementQuantity(CartItem $item, int|null $by)
    {
        $item->increment('quantity', $by ?? 1);

        return $item;
    }

    public function decrementQuantity(CartItem $item, int|null $by)
    {
        $newQuantity = $item->quantity - ($by ?? 1);

        if ($newQuantity <= 0) {
            $this->removeItem($item);
        }

        $item->update(['quantity' => $newQuantity]);

        return $item;
    }

    public function removeItem(CartItem $item)
    {
        $item->delete();

        return 'Item removed successfully.';
    }

    public function clear(User $user)
    {
        $user->cart->items()->delete();

        return $user->cart->fresh('items');
    }

    /**
     * Get a matching cart item for a user and product.
     */
    protected function getCartItem(User $user, int $productId, ?string $color = null)
    {
        $query = $user->cart
            ->items()
            ->where('product_id', $productId);

        if ($color !== null) {
            $query->where('color', $color);
        }

        return $query->first();
    }
}
