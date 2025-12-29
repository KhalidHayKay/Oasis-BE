<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreOrderRequest $request, Cart $cart)
    {
        $user = $request->user();
        $data = $request->validated();

        try {
            $result = $this->service->make($data, $cart, $user);

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
