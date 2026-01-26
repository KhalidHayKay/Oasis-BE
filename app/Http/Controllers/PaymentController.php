<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckoutSessionResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $service) {}

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate(['checkout_token' => 'required|string|exists:checkout_sessions,public_token']);

        $data = $this->service->initialize($user, $data);

        return response()->json([
            'checkoutSession' => CheckoutSessionResource::make($data['session']),
            'clientSecret'    => $data['client_secret'],
            'reference'       => $data['reference'],
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
