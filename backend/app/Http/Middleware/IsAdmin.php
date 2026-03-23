<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard = "web"): Response
    {
        if (Auth::guard($guard)->user()->role !== 'admin') {
            if($request->expectsJson()) {
                return response()->json([
                    'message' => 'You are not an admin.'
                ], 401);
            }
            return redirect()->route('home')->with('error', 'You are not an admin.');
        }
        return $next($request);
    }
}
