<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTemporaryBan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status === 2) {
            if (now()->lt(Auth::user()->temporary_ban_until)) {
                Auth::logout();
                return redirect()->route('login')->with('error',
                    'Your account is temporarily banned until '.Auth::user()->temporary_ban_until->format('Y-m-d'));
            } else {
                Auth::user()->update([
                    'status' => 1,
                    'strikes' => 0,
                    'temporary_ban_until' => null
                ]);
            }
        }

        return $next($request);
    }
}
