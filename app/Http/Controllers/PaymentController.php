<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckoutSessionResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $service) {}

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate(['checkout_token' => 'required|string|exists:checkout_sessions,public_token']);

        $data = $this->service->initialize($user, $data);

        return response()->json([
            'checkoutSession' => CheckoutSessionResource::make($data->session),
            'clientSecret'    => $data->clientSecret,
            'reference'       => $data->reference,
        ]);

    }

    public function confirm(Request $request)
    {
        $user = $request->user();
        $data = $request->validate(['reference' => 'required|string|exists:payments,transaction_reference']);

        $result = $this->service->confirm($user, $data['reference']);

        return response()->json($result);
    }

    public function show(Payment $payment)
    {
        //
    }
}
