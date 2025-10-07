<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckVolunteerApproved
{
    public function handle(Request $request, Closure $next)
    {
        $volunteer = Auth::guard('volunteer')->user();

        if ($volunteer && $volunteer->status == 0) {
            Auth::guard('volunteer')->logout();
            return redirect()->route('login')->with('error', 'Your account is not yet approved by admin.');
        }

        return $next($request);
    }
}
