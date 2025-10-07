<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VolunteerApprovalNotification;
use App\Models\Ngo;
use App\Models\VolunteerBooking;
use Illuminate\Support\Str;

class VolunteerController extends Controller
{
    public function index()
    {
        $volunteerRole = Role::where('name', 'volunteer')->first();
        $volunteers = User::whereHas('roles', function ($query) use ($volunteerRole) {
            $query->where('role_id', $volunteerRole->id);
        })
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'staff');
            })
            ->latest()
            ->get();

        return view('admin.pages.volunteers.index', compact('volunteers'));
    }

    public function bookings($id)
    {
        $volunteer = User::with(['bookings.ngo'])->findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$volunteer->roles()->where('role_id', $volunteerRole->id)->exists()) {
            abort(403, 'This user is not a volunteer');
        }

        return view('admin.pages.volunteers.bookings', compact('volunteer'));
    }

    public function cancelBooking(User $volunteer, VolunteerBooking $booking)
    {
        if ($booking->volunteer_id !== $volunteer->id) {
            abort(403);
        }

        if ($booking->status !== 'booked') {
            return redirect()->back()->with('error', 'Only booked slots can be cancelled');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Booking cancelled successfully');
    }

    public function tasks(User $volunteer)
    {
        return view('admin.pages.volunteers.tasks', compact('volunteer'));
    }
    public function approve($id)
    {
        $volunteer = User::findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$volunteer->roles()->where('role_id', $volunteerRole->id)->exists()) {
            abort(403, 'This user is not a volunteer');
        }

        $token = Str::random(60);
        $volunteer->update([
            'status' => 1,
            'setup_token' => $token,
            'setup_token_expires' => now()->addHours(24)
        ]);

        Mail::to($volunteer->email)->send(new VolunteerApprovalNotification($volunteer));

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Volunteer approved successfully!'
        ]);
    }

    public function create()
    {
        return view('admin.pages.volunteers.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$volunteerRole) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Volunteer role not found in the system!'
            ]);
        }

        $volunteer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'status' => 1,
        ]);

        $volunteer->roles()->attach($volunteerRole->id);

        return redirect()->route('admin.volunteers.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Volunteer created successfully!'
        ]);
    }
    public function edit($id)
    {
        $volunteer = User::findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$volunteer->roles()->where('role_id', $volunteerRole->id)->exists()) {
            abort(403, 'This user is not a volunteer');
        }

        return view('admin.pages.volunteers.edit', compact('volunteer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed', // password validation
        ]);

        $volunteer = User::findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$volunteer->roles()->where('role_id', $volunteerRole->id)->exists()) {
            abort(403, 'This user is not a volunteer');
        }

        $data = $request->only(['name', 'email', 'phone_number']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $volunteer->update($data);

        return redirect()->route('admin.volunteers.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Volunteer updated successfully!'
        ]);
    }


    public function destroy($id)
    {
        $volunteer = User::findOrFail($id);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$volunteer->roles()->where('role_id', $volunteerRole->id)->exists()) {
            abort(403, 'This user is not a volunteer');
        }

        $volunteer->delete();

        return redirect()->route('admin.volunteers.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Volunteer deleted successfully!'
        ]);
    }

    public function bookSlot($volunteerId)
    {
        $volunteer = User::findOrFail($volunteerId);
        $volunteerRole = Role::where('name', 'volunteer')->first();

        if (!$volunteer->roles()->where('role_id', $volunteerRole->id)->exists()) {
            abort(403, 'This user is not a volunteer');
        }

        $ngos = Ngo::where('status', 1)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.pages.volunteers.book-slot', compact('volunteer', 'ngos'));
    }

    public function storeBooking(Request $request, $volunteerId)
    {
        $request->validate([
            'ngo_id' => 'required|exists:ngos,id',
        ]);

        $volunteer = User::findOrFail($volunteerId);
        $ngo = Ngo::findOrFail($request->ngo_id);

        $existingBooking = VolunteerBooking::where('ngo_id', $ngo->id)
            ->where('volunteer_id', $volunteer->id)
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'This volunteer has already booked this slot!'
            ]);
        }

        $bookedCount = VolunteerBooking::where('ngo_id', $ngo->id)
            ->whereIn('status', ['booked', 'checked_in'])
            ->count();

        if ($bookedCount >= $ngo->volunteers_needed) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'No available slots left for this event!'
            ]);
        }

        $bookingCode = 'ADM-' . strtoupper(Str::random(6)) . '-' . date('md');

        VolunteerBooking::create([
            'volunteer_id' => $volunteer->id,
            'ngo_id' => $ngo->id,
            'booking_code' => $bookingCode,
            'booking_date' => $ngo->date,
            'start_time' => $ngo->start_time,
            'end_time' => $ngo->end_time,
            'status' => 'booked',
            'booked_by_admin' => true,
        ]);

        return redirect()->route('admin.volunteers.bookings', $volunteer->id)->with('toast', [
            'type' => 'success',
            'message' => 'Slot booked successfully! Booking code: ' . $bookingCode
        ]);
    }
}
