<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerCheckoutRequest;
use Illuminate\Http\Request;

class CheckoutRequestController extends Controller
{
    public function index()
    {
        $requests = VolunteerCheckoutRequest::with(['checkin.booking.ngo', 'volunteer'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.checkout-requests.index', compact('requests'));
    }

   public function approve($id)
    {
        $request = VolunteerCheckoutRequest::findOrFail($id);

        $checkinTime = $request->checkin->checkin_time;
        $checkoutTime = $request->checkout_time;
        $totalMinutes = $checkinTime->diffInMinutes($checkoutTime);
        $lunchDuration = $request->lunch_duration;
        $workingMinutes = max(0, $totalMinutes - $lunchDuration);

        $totalHours = floor($workingMinutes / 60);
        $remainingMinutes = $workingMinutes % 60;
        $totalWorkingHours = sprintf('%02d:%02d:00', $totalHours, $remainingMinutes);

        $request->checkin->update([
            'checkout_time' => $checkoutTime,
            'lunch_duration' => $lunchDuration,
            'total_working_hours' => $totalWorkingHours,
            'notes' => $request->notes,
            'status' => 'completed'
        ]);

        $request->checkin->booking->update(['status' => 'checked_out']);

        $volunteer = $request->volunteer;
        $volunteer->increment('points', 10);

        $request->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Checkout request approved successfully! +10 points awarded'
        ]);
    }

    public function reject($id)
    {
        $request = VolunteerCheckoutRequest::findOrFail($id);

        $request->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Checkout request rejected!'
        ]);
    }
}
