<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        logger('TEST LOG FROM MIDDLEWARE');

        // Extract token from cookie
        if ($token = $request->cookie('auth_token')) {
            // Set it as Authorization header so Sanctum can find it
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        \Log::info('Authorization header:', [
            'auth' => $request->header('Authorization'),
        ]);

        \Log::info('Cookies:', $request->cookies->all());

        return $next($request);
    }
}
