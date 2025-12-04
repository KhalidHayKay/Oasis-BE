<?php

namespace App\Services;

use App\Models\Category;

class TagService
{
    public function byCategory()
    {
        $data = Category::with([
            'tags' => function ($query) {
                // Count the number of products related to each tag
                $query->withCount('products');
            }
        ])->get();

        return $data;
    }
}
