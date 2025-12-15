<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Laravel\Socialite\Socialite;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

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

            $this->authService->socialLogin($socialUser, $provider);

            // Find or create user
            // $user = User::updateOrCreate(
            //     ['email' => $socialUser->getEmail()],
            //     [
            //         'name' => $socialUser->getName(),
            //         'provider' => $provider,
            //         'provider_id' => $socialUser->getId(),
            //         'avatar' => $socialUser->getAvatar(),
            //     ]
            // );

            // Generate Sanctum token
            // $token = $user->createToken('auth-token')->plainTextToken;

            // Redirect back to frontend with token
            // $frontendUrl = config('app.frontend_url');
            // return redirect()->away("{$frontendUrl}/auth/callback?token={$token}");

        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
            // $frontendUrl = config('app.frontend_url');
            // return redirect()->away("{$frontendUrl}/auth/error?message=" . urlencode($e->getMessage()));
        }
    }
}
