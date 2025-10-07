<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RoleVolunteerApprovedNotification;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\PasswordResetNotification;
use App\Mail\VolunteerRegistrationAdminNotification;
use App\Mail\VolunteerApprovalNotification;
use App\Mail\RoleVolunteerRequestNotification;
use App\Models\Ngo;
use App\Models\VolunteerBooking;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('roles.auth.login');
    }

    public function showUserLoginForm()
    {
        return view('roles.auth.user_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status != 1) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is pending approval']);
            }

            $role = $user->roles()->first();

            if ($role) {
                switch ($role->name) {
                    case 'staff':
                        return redirect()->route('staff.dashboard');
                    case 'volunteer':
                        return redirect()->route('volunteer.dashboard');
                    case 'user':
                        return redirect()->route('user.dashboard');
                    default:
                        return redirect()->route('user.dashboard');
                }
            }

            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function showVolunteerRegisterForm()
    {
        return view('roles.auth.register');
    }
    public function showRegistrationSuccess()
    {
        return view('roles.auth.registration-success');
    }

    public function volunteerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => 0,
            'approval_token' => Str::random(60),
            'approval_token_expires' => now()->addDays(3),
        ]);

        $volunteerRole = Role::where('name', 'volunteer')->first();
        $user->roles()->attach($volunteerRole->id);

    Mail::to('neomisule@gmail.com')->send(new VolunteerRegistrationAdminNotification($user));

        return redirect()->route('registration.success')->with('role', 'volunteer');
    }

    public function approveFromEmail($token)
    {
        $volunteer = User::where('approval_token', $token)
            ->where('approval_token_expires', '>', now())
            ->where('status', 0)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'volunteer');
            })
            ->firstOrFail();

        $passwordToken = Str::random(60);

        $volunteer->update([
            'status' => 1,
            'approval_token' => null,
            'approval_token_expires' => null,
            'setup_token' => $passwordToken,
            'setup_token_expires' => now()->addHours(24)
        ]);

        Mail::to($volunteer->email)->send(new VolunteerApprovalNotification($volunteer));

        return redirect()->route('volunteer.approval.success')
            ->with('volunteer', $volunteer);
    }

    public function showVolunteerPasswordSetup($token)
    {
        $volunteer = user::where('setup_token', $token)
            ->where('setup_token_expires', '>', now())
            ->firstOrFail();

        return view('roles.auth.set-password', compact('token'));
    }

    public function setupVolunteerPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $volunteer = user::where('setup_token', $token)
            ->where('setup_token_expires', '>', now())
            ->firstOrFail();

        $volunteer->update([
            'password' => Hash::make($request->password),
            'setup_token' => null,
            'setup_token_expires' => null,
            'status' => 1
        ]);

        Auth::login($volunteer);

        return redirect()->route('volunteer.dashboard');
    }

    public function showUserRegisterForm()
    {
        return view('roles.auth.user_register');
    }

    public function userRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'status' => 1
        ]);

        $customerRole = Role::where('name', 'user')->first();
        if ($customerRole) {
            $user->roles()->attach($customerRole->id);
        }

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }

    // Common Methods
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showNgoSelection()
    {
        $ngos = Ngo::where('status', 1)
            ->orderBy('date', 'desc')
            ->get();

        return view('ngo-selection', compact('ngos'));
    }

    public function showVolunteerList(Request $request)
    {
        $request->validate([
            'ngo_id' => 'required|exists:ngos,id'
        ]);

        $ngo = Ngo::findOrFail($request->ngo_id);

        $volunteers = VolunteerBooking::with('volunteer')
            ->where('ngo_id', $request->ngo_id)
            ->whereIn('status', ['booked', 'checked_in', 'checked_out'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('volunteers_list', compact('ngo', 'volunteers'));
    }

    public function showBecomeVolunteerForm()
    {
        return view('roles.auth.become_volunteer');
    }

    public function requestBecomeVolunteer(Request $request)
    {
        $user = auth()->user();
        if (auth()->user()->hasAnyRole(['volunteer'])) {
            return redirect()->route('home')->with('error', 'This feature is only for regular users');
        }

        if ($user->hasRole('volunteer')) {
            return redirect()->back()->with('error', 'You are already a volunteer');
        }

        if ($user->volunteer_approval_token) {
            return redirect()->back()->with('error', 'You already have a pending volunteer request');
        }

        $user->update([
            'volunteer_approval_token' => Str::random(60),
            'volunteer_token_expires' => now()->addDays(3),
            'volunteer_requested_at' => now()
        ]);
        $type = $user->hasRole('staff') ? 'staff' : 'user';
        Mail::to('infovishalsinghrajput@gmail.com')->send(new RoleVolunteerRequestNotification($user, $type));
        if ($user->hasRole('staff')) {
            return redirect()->route('staff.dashboard')->with('success', 'Your request to become a volunteer has been submitted. Please wait for admin approval.');
        } else {
            return redirect()->route('user.dashboard')->with('success', 'Your request to become a volunteer has been submitted. Please wait for admin approval.');
        }
    }

    public function approveStaffVolunteer($token)
    {
        $user = User::where('volunteer_approval_token', $token)
            ->where('volunteer_token_expires', '>', now())
            ->whereNotNull('volunteer_requested_at')
            ->firstOrFail();
        $volunteerRole = Role::where('name', 'volunteer')->first();
        $user->roles()->attach($volunteerRole->id);

        $user->update([
            'volunteer_approval_token' => null,
            'volunteer_requested_at' => null,
            'volunteer_token_expires' => null,
            'volunteer_approved_at' => now()
        ]);
        Mail::to($user->email)->send(new RoleVolunteerApprovedNotification($user));

        return redirect()->route('volunteer.approval.success')
            ->with('user', $user);
    }

      public function showForgotPasswordForm()
    {
        return view('roles.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'The provided email address does not exist in our system.'
        ]);

        // Check if user exists and is active
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'The provided email address does not exist in our system.']);
        }

        if ($user->status != 1) {
            return back()->withErrors(['email' => 'Your account is pending approval. Please contact administrator.']);
        }

        // Generate token
        $token = Str::random(60);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Send email
        Mail::to($request->email)->send(new PasswordResetNotification($token, $user));

        return back()->with('status', 'Password reset link has been sent to your email address.');
    }

    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token = null)
    {
        // Verify token exists and is valid
        $tokenExists = DB::table('password_reset_tokens')
            ->where('token', '!=', '') // Ensure we have a valid token entry
            ->get()
            ->filter(function ($record) use ($token) {
                return Hash::check($token, $record->token);
            })
            ->first();

        if (!$tokenExists) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'This password reset token is invalid or has expired.']);
        }

        // Check if token is expired (24 hours)
        $tokenAge = Carbon::parse($tokenExists->created_at);
        if ($tokenAge->diffInHours(Carbon::now()) > 24) {
            // Delete expired token
            DB::table('password_reset_tokens')->where('email', $tokenExists->email)->delete();

            return redirect()->route('password.request')
                ->withErrors(['token' => 'This password reset token has expired. Please request a new one.']);
        }

        return view('roles.auth.reset-password', ['token' => $token, 'email' => $tokenExists->email]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify token
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            return back()->withErrors(['email' => 'Invalid password reset request.']);
        }

        // Verify token matches
        if (!Hash::check($request->token, $tokenRecord->token)) {
            return back()->withErrors(['token' => 'Invalid password reset token.']);
        }

        // Check if token is expired
        $tokenAge = Carbon::parse($tokenRecord->created_at);
        if ($tokenAge->diffInHours(Carbon::now()) > 24) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'Password reset token has expired. Please request a new one.']);
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Send success notification
        Mail::to($user->email)->send(new \App\Mail\PasswordResetSuccessNotification($user));

        // Auto-login the user after password reset (optional)
        Auth::login($user);

        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully. You are now logged in.');
    }
}
