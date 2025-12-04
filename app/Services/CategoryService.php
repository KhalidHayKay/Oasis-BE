<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;

class CategoryService
{
    public function categories(array $filters)
    {
        $query = Category::withCount('products');

        if (! empty($filters['sort_by']) && $filters['sort_by'] === 'popularity') {
            $query->orderBy('popularity_score', 'desc');
        }

        if (! empty($filters['max'])) {
            $query->take($filters['max']);
        }

        return $query->get();
    }

    public function productsByCategorySlug(string $slug, ?string $sortKey = null)
    {
        $category = Category::where('slug', $slug)->withCount('products')->firstOrFail();

        $tags = $category->tags;

        $query = $category->products()->with(['featuredImage', 'category', 'tags']);

        if ($sortKey) {
            match ($sortKey) {
                'price_low'  => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'name'       => $query->orderBy('name', 'asc'),
                default      => $query->latest()
            };
        }

        $products = $query->paginate(40);

        $related = Product::where('category_id', '!=', $category->id)
            ->with(['featuredImage', 'category'])
            ->inRandomOrder()
            ->limit(20)
            ->get();

        return [$category, $tags, $products, $related];
    }
}
