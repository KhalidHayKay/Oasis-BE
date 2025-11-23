<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function top()
    {
        $products = $products = Product::with(['featuredImage', 'categories'])
            ->latest() // Later: ->where('is_top_product', true)
            ->limit(8)
            ->get();

        return $products;
    }

    public function product(Product $product)
    {
        $product = $product->load(['images', 'featuredImage', 'categories']);

        $related = Product::whereHas('categories', function ($q) use ($product) {
            $q->whereIn('categories.id', $product->categories->pluck('id'));
        })
            ->where('id', '!=', $product->id)
            ->with('featuredImage')
            ->limit(4)
            ->get();

        return [$product, $related];
    }
}
