<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomSanctumAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request has a valid access token
        if (Auth::guard('sanctum')->check() || $request->expectsJson()) {
            // If the access token is valid or the request expects JSON, set the user in the request
            $request->setUserResolver(function () {
                return Auth::guard('sanctum')->user();
            });

            // If the access token is valid or the request expects JSON, proceed with the request
            return $next($request);
        }

        // If the access token is not valid and the request does not expect JSON, return an unauthorized response
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
}
