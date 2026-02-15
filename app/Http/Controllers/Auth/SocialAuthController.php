<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Laravel\Socialite\Socialite;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\SocialiteManager;

class SocialAuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function redirect(Request $request, string $provider)
    {
        return Socialite::driver($provider)
            ->stateless()
            ->redirect();
    }

    public function callback(Request $request, string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();

            $response = $this->authService->socialLogin($socialUser, $provider);

            $cookie = cookie('auth_token', $response->token, 60);

            return response()->view('auth.popup')->cookie($cookie);
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
            // $frontendUrl = config('app.frontend_url');
            // return redirect()->away("{$frontendUrl}/auth/error?message=" . urlencode($e->getMessage()));
        }
    }
}
