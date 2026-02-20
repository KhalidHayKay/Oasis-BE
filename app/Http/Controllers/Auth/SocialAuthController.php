<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Laravel\Socialite\Socialite;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function redirect(Request $request, string $provider)
    {
        $returnPath = $request->query('return_path', '/');
        $origin     = $request->query('origin');

        $allowedOrigins = config('cors.allowed_origins');

        if (! $origin || ! in_array($origin, $allowedOrigins)) {
            abort(403, 'Invalid origin');
        }

        return Socialite::driver($provider)
            ->stateless()
            ->with([
                'state' => base64_encode(json_encode([
                    'return_path' => $returnPath,
                    'origin'      => $origin,
                ])),
            ])
            ->redirect();
    }

    public function callback(Request $request, string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();

            $response = $this->authService->socialLogin($socialUser, $provider);

            $exchangeToken = Str::random(64);
            Cache::put(
                "oauth_exchange:{$exchangeToken}",
                [
                    'token' => $response->token,
                    'user'  => $response->user,
                ],
                now()->addMinutes(5)
            );

            $state = $request->query('state');

            $redirectUrl = $this->constructRedirectUrl($state);

            // Pass exchange token
            return redirect()->away("{$redirectUrl}?exchange_token={$exchangeToken}");

        } catch (\Exception $e) {
            Log::error($e);

            $state = $request->query('state');

            $redirectUrl = $this->constructRedirectUrl($state);

            return redirect()->away("{$redirectUrl}?error=" . urlencode($e->getMessage()));
        }
    }

    public function exchange(Request $request)
    {
        $exchangeToken = $request->input('exchange_token');

        if (! $exchangeToken) {
            return response()->json(['message' => 'Exchange token required'], 400);
        }

        $data = Cache::pull("oauth_exchange:{$exchangeToken}");

        if (! $data) {
            return response()->json(['message' => 'Invalid or expired exchange token'], 401);
        }

        $cookie = cookie(
            'auth_token',
            $data['token'],
            60,    // 60 minutes
            '/',   // path
            null,  // domain
            true,  // secure (HTTPS only)
            true   // httpOnly (JavaScript can't read)
        );

        return response()->json([
            'message' => 'Authentication successful',
            'user'    => UserResource::make($data['user']),
        ])->cookie($cookie);
    }

    protected function constructRedirectUrl(string $state)
    {
        // Defaults
        $frontendUrl = config('app.frontend_url');
        $returnPath  = '/';

        if ($state) {
            $decoded    = json_decode(base64_decode($state), true);
            $returnPath = $decoded['return_path'] ?? '/';
            $origin     = $decoded['origin'] ?? null;

            // Validate origin from state
            $allowedOrigins = config('cors.allowed_origins');
            if ($origin && in_array($origin, $allowedOrigins)) {
                $frontendUrl = $origin;
            }
        }

        return rtrim($frontendUrl, '/') . '/' . ltrim($returnPath, '/');
    }
}
