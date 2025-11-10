<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyExportToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken(); // Checks for "Authorization: Bearer <token>"
        $secret = env('EXPORT_HOME_SECRET');

        if (!$token || $token !== $secret) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}
