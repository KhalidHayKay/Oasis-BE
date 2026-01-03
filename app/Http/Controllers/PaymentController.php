<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $service) {}

    public function index()
    {
        //
    }

    public function store(Order $order)
    {
        $data = $this->service->initialize($order);

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
        ]);

    }

    public function show(Payment $payment)
    {
        //
    }

    public function update(Payment $payment)
    {
        //
    }

    public function destroy(Payment $payment)
    {
        //
    }
}
