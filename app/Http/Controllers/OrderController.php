<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $service) {}

    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'orders' => $user->orders,
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $user = $request->user();
        $request->merge(['user' => $user]);
        $data = $request->validated();

        try {
            $result = $this->service->makeFromCart($data, $user);

            return response()->json([
                'message'  => 'Order created successfully',
                'order'    => $result['order'],
                'nextStep' => 'payment',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Order $order)
    {
        //
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        //
    }
}
