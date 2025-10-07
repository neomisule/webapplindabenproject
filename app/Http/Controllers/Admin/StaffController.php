<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\RoleVolunteerApprovedNotification;

class StaffController extends Controller
{
    public function index()
    {
        $staffRole = Role::where('name', 'staff')->first();
        $staff = User::whereHas('roles', function ($query) use ($staffRole) {
            $query->where('role_id', $staffRole->id);
        })
            ->with(['roles'])
            ->latest()
            ->get();
        // dd($staff);
        return view('admin.pages.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.pages.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $staffRole = Role::where('name', 'staff')->first();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'status' => 1
        ]);

        $user->roles()->attach($staffRole->id);

        return redirect()->route('admin.staff.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Staff member created successfully!'
        ]);
    }

    public function edit($id)
    {
        $staff = User::findOrFail($id);
        $staffRole = Role::where('name', 'staff')->first();

        if (!$staff->roles()->where('role_id', $staffRole->id)->exists()) {
            abort(403, 'This user is not a staff member');
        }

        return view('admin.pages.staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $staff = User::findOrFail($id);
        $staffRole = Role::where('name', 'staff')->first();

        if (!$staff->roles()->where('role_id', $staffRole->id)->exists()) {
            abort(403, 'This user is not a staff member');
        }

        $data = $request->only(['name', 'email', 'phone_number']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Staff member updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $staff = User::findOrFail($id);
        $staffRole = Role::where('name', 'staff')->first();

        if (!$staff->roles()->where('role_id', $staffRole->id)->exists()) {
            abort(403, 'This user is not a staff member');
        }

        $staff->roles()->detach($staffRole->id);

        return redirect()->route('admin.staff.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Staff member removed successfully!'
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $userId = $request->input('id');
        $newStatus = $request->input('new_status');
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'user not found.']);
        }

        $user->status = $newStatus;
        $user->temporary_ban_until = null;
        $user->save();

        return redirect()->route('admin.staff.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'User status update successfully!'
        ]);
    }

    public function approveRequest($id)
    {
        $user = User::findOrFail($id);

        if (!$user->volunteer_requested_at || $user->volunteer_approved_at) {
            return redirect()->route('admin.staff.index')->with('toast', [
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

        return redirect()->route('admin.staff.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Volunteer request approved successfully!'
        ]);
    }
    public function addVolunteerRole($id)
    {
        $staff = User::findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$staff->hasRole('volunteer')) {
            $staff->roles()->attach($volunteerRole->id);
            $staff->update([
                'volunteer_approval_token' => null,
                'volunteer_requested_at' => null,
                'volunteer_token_expires' => null,
                'volunteer_approved_at' => null
            ]);
            return redirect()->route('admin.staff.index')->with('toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Volunteer role added successfully!'
            ]);
        }

        return redirect()->route('admin.staff.index')->with('toast', [
            'type' => 'warning',
            'title' => 'Warning',
            'message' => 'Staff member already has volunteer role'
        ]);
    }

    public function removeVolunteerRole($id)
    {
        $staff = User::findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if ($staff->hasRole('volunteer')) {
            $staff->roles()->detach($volunteerRole->id);

            return redirect()->route('admin.staff.index')->with('toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'Volunteer role removed successfully!'
            ]);
        }

        return redirect()->route('admin.staff.index')->with('toast', [
            'type' => 'warning',
            'title' => 'Warning',
            'message' => 'Staff member doesn\'t have volunteer role'
        ]);
    }
}
