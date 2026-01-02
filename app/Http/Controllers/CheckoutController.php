<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use App\Services\CheckoutService;

class CheckoutController extends Controller
{
    public function __construct(private readonly CheckoutService $service) {}

    public function validate(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart;

        $result = $this->service->validateCartForCheckout($cart, $user);

        if (! $result['ok']) {
            return response()->json([
                'status' => 'invalid',
                'reason' => $result['reason'],
                'issues' => $result['issues'],
            ], 422);
        }

        return response()->json([
            'status'                 => 'ok',
            'checkout_session_token' => $result['checkout_session_id']->public_token,
            'expires_at'             => $result['checkout_session_id']->expires_at,
            'saved_addresses'        => $result['saved_addresses'] ?? [],
        ]);
    }

    public function attachCustomer(CheckoutRequest $request)
    {
        $user = $request->user();
        $request->merge(['user' => $user]);
        $data = $request->validated();

        $checkoutSession = $this->service->attachCustomerAndAddress(
            user: $user,
            data: $data
        );

        return response()->json([
            'status'           => 'ok',
            'checkout_session' => $checkoutSession,
        ]);
    }
}
