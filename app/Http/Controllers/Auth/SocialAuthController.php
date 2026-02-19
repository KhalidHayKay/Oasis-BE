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
        $returnUrl = $request->query('return_path', '/');

        return Socialite::driver($provider)
            ->stateless()
            ->with(['state' => base64_encode(json_encode(['return_path' => $returnUrl]))])
            ->redirect();
    }

    public function callback(Request $request, string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();

            $response = $this->authService->socialLogin($socialUser, $provider);

            // Create short-lived exchange token (5 minutes)
            $exchangeToken = Str::random(64);
            Cache::put(
                "oauth_exchange:{$exchangeToken}",
                [
                    'token' => $response->token,
                    'user'  => $response->user, // Store user object
                ],
                now()->addMinutes(5)
            );

            $requestOrigin = $request->headers->get('origin') ?? $request->headers->get('referer');

            // Validate against whitelist
            $allowedOrigins = config('cors.allowed_origins');
            $frontendUrl    = config('app.frontend_url'); // default

            if ($requestOrigin) {
                $parsedOrigin = parse_url($requestOrigin, PHP_URL_SCHEME) . '://' . parse_url($requestOrigin, PHP_URL_HOST);
                if (in_array($parsedOrigin, $allowedOrigins)) {
                    $frontendUrl = $parsedOrigin;
                }
            }

            $state      = $request->query('state');
            $returnPath = '/';

            if ($state) {
                $decoded    = json_decode(base64_decode($state), true);
                $returnPath = $decoded['return_path'] ?? '/';
            }

            $redirectUrl = rtrim($frontendUrl, '/') . '/' . ltrim($returnPath, '/');

            // Pass exchange token (not actual auth token)
            return redirect()->away("{$redirectUrl}?exchange_token={$exchangeToken}");

        } catch (\Exception $e) {
            Log::error($e);
            $frontendUrl = config('app.frontend_url');
            return redirect()->away("{$frontendUrl}?error=" . urlencode($e->getMessage()));
        }
    }

    public function exchange(Request $request)
    {
        $exchangeToken = $request->input('exchange_token');

        if (! $exchangeToken) {
            return response()->json(['message' => 'Exchange token required'], 400);
        }

        // Get token and user data from cache
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
            'user'    => UserResource::make($data['user']) // Return user with resource
        ])->cookie($cookie);
    }
}
