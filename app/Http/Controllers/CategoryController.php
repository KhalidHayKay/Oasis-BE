<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Resources\TagResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $service) {}

    public function index()
    {
        $categories = $this->service->categories();

        return response()->json([
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    public function show(Request $request, $slug)
    {
        [$category, $tags, $products, $related] = $this->service->productsByCategorySlug($slug, $request->query('sort'));

        return response()->json([
            'category'        => CategoryResource::make($category),
            'tags'            => TagResource::collection($tags),
            'products'        => ProductResource::collection($products),
            'relatedProducts' => ProductResource::collection($related),
        ]);
    }

}
