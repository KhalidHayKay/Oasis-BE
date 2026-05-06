<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accept = strtolower($request->header('Accept'));

        $allowedAPIRoutes = [
            'api/auth/provider/*',
        ];

        // If Accept header is present, not JSON, and route doesn't match any allowed pattern
        if (
            $accept &&
            stripos($accept, 'application/json') === false &&
            $request->is('api/*') &&
            ! collect($allowedAPIRoutes)->contains(fn ($pattern) => $request->is($pattern))
        ) {
            return response()->json([
                'error' => 'Only Accept: application/json is supported.',
            ], 406);
        }

        if (! $accept) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
