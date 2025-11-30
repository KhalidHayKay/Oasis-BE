<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function top()
    {
        $products = $products = Product::with(['featuredImage', 'category'])
            ->orderBy('popularity_score', 'desc')
            ->limit(48)
            ->get();

        return $products;
    }

    public function product(Product $product)
    {
        $product = $product->load(['images', 'featuredImage', 'category']);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('featuredImage')
            ->limit(10)
            ->get();

        return [$product, $related];
    }
}
