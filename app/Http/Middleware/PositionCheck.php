<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$positions){
        foreach ($positions as $position) {
            if (Auth::check() && Auth::user()->position->name == $position) {
                return $next($request);
            }
        }
        return redirect()->route('dashboard')->withErrors('You are not authorized to access this page.');
    }
}