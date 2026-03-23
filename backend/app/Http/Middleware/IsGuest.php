<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard = "web"): Response
    {
        if (Auth::guard($guard)->check()) {
            if($request->expectsJson()) {
                return response()->json([
                    'message' => 'You are already logged in.'
                ], 401);
            }
            return redirect()->route('home')->with('error', 'You are already logged in.');
        }
        return $next($request);
    }
}
