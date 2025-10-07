<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        if (auth()->guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');
        $user = User::where('username', $credentials['username'])->first();


        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'The provided credentials do not match our records.',
                ]);
        }


        if (!Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'password' => 'Incorrect password.',
                ]);
        }


        $isAdmin = $user->roles()->where('name', 'admin')->exists();

        if (!$isAdmin) {
            return redirect()->back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'Only administrators are allowed to login here.',
                ]);
        }


        if ($user->status != 1) {
            return redirect()->back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'Your account is pending approval.',
                ]);
        }


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Logged in successfully');
        }

        return redirect()->back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => 'Login failed. Please try again.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function Adminlogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
