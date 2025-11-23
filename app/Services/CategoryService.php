<?php

namespace App\Services;

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

        $query = $category->products()->with(['featuredImage', 'categories']);

        if ($sortKey) {
            match ($sortKey) {
                'price_low'  => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'name'       => $query->orderBy('name', 'asc'),
                default      => $query->latest()
            };
        }

        // $products = $query->get();
        $products = $query->paginate(12);

        return [$category, $products];
    }
}
