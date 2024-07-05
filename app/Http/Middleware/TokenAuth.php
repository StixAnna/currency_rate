<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token || !Str::startsWith($token, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $token = Str::substr($token, 7); // Remove 'Bearer ' prefix

        if ($token !== config('auth.api_token')) {
            return response()->json(['error' => 'Invalid token'], 403);
        }

        return $next($request);
    }
}
