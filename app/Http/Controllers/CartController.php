<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $service,
    ) {}

    public function show(Request $request)
    {
        return response()->json(
            $this->service->show($request->user())
        );
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = $this->service->addItem($data, $request->user());

        return response()->json($cart, 201);
    }

    public function remove(Request $request, Product $product)
    {
        $cart = $this->service->removeItem(
            $request->user(),
            $product
        );

        return response()->json($cart);
    }

    public function incrementQ(Request $request, Product $product)
    {
        $data = $request->validate([
            'by' => 'nullable|integer|min:1',
        ]);

        $cart = $this->service->incrementQuantity(
            $request->user(),
            $product,
            $data['by'] ?? 1
        );

        return response()->json($cart);
    }

    public function decrementQ(Request $request, Product $product)
    {
        $data = $request->validate([
            'by' => 'nullable|integer|min:1',
        ]);

        $cart = $this->service->decrementQuantity(
            $request->user(),
            $product,
            $data['by'] ?? 1
        );

        return response()->json($cart);
    }

    public function clear(Request $request)
    {
        $cart = $this->service->clear(
            $request->user()
        );

        return response()->json($cart);
    }
}
