<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    public function __construct(readonly protected AuthService $service) {}

    public function me(Request $request)
    {
        return UserResource::make($request->user());
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $res = $this->service->login($data);

        $cookie = $this->makeCookie($res->token);

        return response()->json([
            'message' => 'Login successful',
            'user'    => UserResource::make($res->user),
        ])->cookie($cookie);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $res = $this->service->register($data);

        $cookie = $this->makeCookie($res->token);

        return response()->json([
            'message' => 'Registration successful, A verification code will be sent to your email',
            'user'    => UserResource::make($res->user),
        ], 201)->cookie($cookie);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        $this->service->logout($user);

        $cookie = cookie()->forget('auth_token');

        return response()->json([
            'message' => 'Logged out successfully',
        ])->cookie($cookie);
    }

    private function makeCookie(string $token)
    {
        return cookie('auth_token', $token, 60); // 60 minutes to match token expiration
    }
}
