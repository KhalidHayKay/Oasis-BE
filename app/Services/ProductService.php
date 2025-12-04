<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function all(array $filters)
    {
        $query = Product::with(['featuredImage', 'tags']);

        if (! empty($filters['tags']) && is_array($filters['tags'])) {
            // dd($filters['tags']);
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('slug', $filters['tags']);
            });
        }

        if (! empty($filters['min_price'])) {
            // $query->where('price->amount', '>=', (float) $filters['min_price']);
            $query->whereRaw("JSON_EXTRACT(price, '$.amount') + 0 >= ?", [(float) $filters['min_price']]);
        }

        if (! empty($filters['max_price'])) {
            // $query->where('price->amount', '<=', (float) $filters['max_price']);
            $query->whereRaw("JSON_EXTRACT(price, '$.amount') + 0 <= ?", [(float) $filters['max_price']]);
        }

        return $query->paginate(50);
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
