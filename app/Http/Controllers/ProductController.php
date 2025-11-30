<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductDetailResource;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $service) {}

    public function top()
    {
        return ProductResource::collection($this->service->top());
    }

    public function show(Request $request, Product $product)
    {
        [$product, $related] = $this->service->product($product);

        return response()->json([
            'product'         => ProductDetailResource::make($product),
            'relatedProducts' => productResource::collection($related),
        ]);
    }

}
