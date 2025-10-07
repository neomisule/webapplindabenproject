<?php

namespace App\Http\Controllers\Role\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\VolunteerBooking;
use Illuminate\Http\Request;
use App\Models\VolunteerCheckoutRequest;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = VolunteerBooking::with(['ngo', 'checkin.checkoutRequest'])
            ->where('volunteer_id', auth()->id())
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('roles.volunteer.pages.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = VolunteerBooking::with(['ngo', 'checkin.checkoutRequest'])
            ->where('volunteer_id', auth()->id())
            ->findOrFail($id);

        return view('roles.volunteer.pages.bookings.show', compact('booking'));
    }


    public function cancel($id)
    {
        $booking = VolunteerBooking::where('volunteer_id', auth()->id())
            ->where('status', 'booked')
            ->findOrFail($id);

        $booking->update(['status' => 'cancelled']);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Booking cancelled successfully! The slot is now available for others.'
        ]);
    }
}
