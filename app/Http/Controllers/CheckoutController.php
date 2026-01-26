<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutAddressRequest;
use App\Http\Resources\CheckoutSessionResource;
use Illuminate\Http\Request;
use App\Services\CheckoutService;

class CheckoutController extends Controller
{
    public function __construct(private readonly CheckoutService $service) {}

    public function show(Request $request)
    {
        $user = $request->user();

        $result = $this->service->get($user);

        if (! $result) {
            return response()->json(['message' => 'No active checckout session'], 404);
        }

        return CheckoutSessionResource::make($result);
    }

    public function validate(Request $request)
    {
        $user = $request->user();

        $session = $this->service->validateCartForCheckout($user);

        return response()->json([
            'success' => true,
            'session' => CheckoutSessionResource::make($session),
        ]);
    }

    public function address(CheckoutAddressRequest $request)
    {
        $user = $request->user();
        $request->merge(['user' => $user]);
        $data = $request->validated();

        $checkoutSession = $this->service->address($user, $data);

        return response()->json([
            'checkout_session' => CheckoutSessionResource::make($checkoutSession),
        ]);
    }
}
