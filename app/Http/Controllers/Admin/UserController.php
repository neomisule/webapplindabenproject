<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\RoleVolunteerApprovedNotification;

class UserController extends Controller
{
    public function index()
    {
        $userRole = Role::where('name', 'user')->first();
        $users = User::whereHas('roles', function ($query) use ($userRole) {
            $query->where('role_id', $userRole->id);
        })
            ->with(['roles'])
            ->latest()
            ->get();

        return view('admin.pages.users.index', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userRole = Role::where('name', 'user')->first();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'status' => 1
        ]);

        $user->roles()->attach($userRole->id);

        return redirect()->route('admin.users.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'User created successfully!'
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $userRole = Role::where('name', 'user')->first();

        if (!$user->roles()->where('role_id', $userRole->id)->exists()) {
            abort(403, 'This user is not a customer');
        }

        return view('admin.pages.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $userRole = Role::where('name', 'user')->first();

        if (!$user->roles()->where('role_id', $userRole->id)->exists()) {
            abort(403, 'This user is not a customer');
        }

        $data = $request->only(['name', 'email', 'phone_number']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'User updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userRole = Role::where('name', 'user')->first();

        if (!$user->roles()->where('role_id', $userRole->id)->exists()) {
            abort(403, 'This user is not a customer');
        }

        $user->roles()->detach($userRole->id);

        return redirect()->route('admin.users.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'User removed successfully!'
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $userId = $request->input('id');
        $newStatus = $request->input('new_status');
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->status = $newStatus;
        $user->temporary_ban_until = null;
        $user->save();

        return redirect()->route('admin.users.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'User status updated successfully!'
        ]);
    }

    public function addVolunteerRole($id)
    {
        $user = User::findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$user->hasRole('volunteer')) {
            $user->roles()->attach($volunteerRole->id);

            return redirect()->route('admin.users.index')->with('toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Volunteer role added successfully!'
            ]);
        }

        return redirect()->route('admin.users.index')->with('toast', [
            'type' => 'warning',
            'title' => 'Warning',
            'message' => 'User already has volunteer role'
        ]);
    }
    public function approveRequest($id)
    {
        $user = User::findOrFail($id);

        if (!$user->volunteer_requested_at || $user->volunteer_approved_at) {
            return redirect()->route('admin.users.index')->with('toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No pending volunteer request found'
            ]);
        }

        $volunteerRole = Role::where('name', 'volunteer')->first();
        $user->roles()->attach($volunteerRole->id);

        $user->update([
            'volunteer_approval_token' => null,
            'volunteer_token_expires' => null,
            'volunteer_approved_at' => now()
        ]);

        Mail::to($user->email)->send(new RoleVolunteerApprovedNotification($user));

        return redirect()->route('admin.users.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Volunteer request approved successfully!'
        ]);
    }

    public function removeVolunteerRole($id)
    {
        $user = User::findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if ($user->hasRole('volunteer')) {
            $user->roles()->detach($volunteerRole->id);
            $user->update([
                'volunteer_approval_token' => null,
                'volunteer_requested_at' => null,
                'volunteer_token_expires' => null,
                'volunteer_approved_at' => null
            ]);
            return redirect()->route('admin.users.index')->with('toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Volunteer role removed successfully!'
            ]);
        }

        return redirect()->route('admin.users.index')->with('toast', [
            'type' => 'warning',
            'title' => 'Warning',
            'message' => 'User doesn\'t have volunteer role'
        ]);
    }
}
