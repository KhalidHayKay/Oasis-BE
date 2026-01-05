<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $service,
    ) {}

    public function show(Request $request)
    {
        $items = $this->service->show($request->user());

        return CartItemResource::collection($items);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'color'      => 'required|string|max:255',
        ]);

        $item = $this->service->addItem($data, $request->user());

        // dd($item->image->image_path);

        return response()->json(CartItemResource::make($item), 201);
    }

    public function sync(Request $request)
    {
        $request->validate([
            'items'              => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.color'      => 'required|string',
        ]);

        $user   = $request->user();
        $synced = [];

        foreach ($request->items as $item) {
            $synced[] = $this->service->addItem($item, $user);
        }

        return response()->json([
            'data' => CartItemResource::collection($synced),
        ]);
    }

    public function remove(Request $request, CartItem $item)
    {
        $cart = $this->service->removeItem($item);

        return response()->json($cart);
    }

    public function incrementQ(Request $request, CartItem $item)
    {
        $data = $request->validate([
            'by' => 'nullable|integer|min:1',
        ]);

        $by = isset($data['by']) ? $data['by'] : null;

        $item = $this->service->incrementQuantity($item, $by);

        return CartItemResource::make($item);
    }

    public function decrementQ(Request $request, CartItem $item)
    {
        $data = $request->validate([
            'by' => 'nullable|integer|min:1',
        ]);

        $by = isset($data['by']) ? $data['by'] : null;

        $item = $this->service->decrementQuantity($item, $by);

        return CartItemResource::make($item);
    }

    public function clear(Request $request)
    {
        $cart = $this->service->clear(
            $request->user()
        );

        return response()->json($cart);
    }
}
