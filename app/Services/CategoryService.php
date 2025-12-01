<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;

class CategoryService
{
    public function categories()
    {
        $categories = Category::withCount('products')->get();

        return $categories;
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

        // $products = $query->get();
        $products = $query->paginate(40);

        $related = Product::where('category_id', '!=', $category->id)
            ->with(['featuredImage', 'category'])
            ->inRandomOrder()
            ->limit(20)
            ->get();

        return [$category, $tags, $products, $related];
    }
}
