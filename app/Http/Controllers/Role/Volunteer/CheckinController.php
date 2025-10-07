<?php

namespace App\Http\Controllers\Role\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\VolunteerBooking;
use App\Models\VolunteerCheckin;
use App\Models\VolunteerCheckoutRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller
{
    public function showCheckinForm()
    {
        return view('roles.volunteer.pages.checkin.form');
    }

    public function processCheckin(Request $request)
    {
        try {
            $request->validate([
                'booking_code' => 'required|string|exists:volunteer_bookings,booking_code'
            ]);

            $user = auth()->user();

            if ($user->status === 0 && now()->lt($user->temporary_ban_until)) {
                return response()->json([
                    'message' => 'Your account is temporarily banned until ' . $user->temporary_ban_until->format('Y-m-d')
                ], 403);
            }

            $booking = VolunteerBooking::where('booking_code', $request->booking_code)
                ->where('volunteer_id', $user->id)
                ->firstOrFail();

            if ($booking->status !== 'booked') {
                return response()->json([
                    'message' => 'This booking is not eligible for check-in. Status: ' . $booking->status
                ], 422);
            }
            $bookingDate = Carbon::parse($booking->booking_date);

            $currentTime = now();
            $endTimeString = $booking->end_time;
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $endTimeString)) {
                $bookingEndTime = Carbon::parse($endTimeString);
            } else if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $endTimeString) || preg_match('/^\d{2}:\d{2}$/', $endTimeString)) {
                $bookingEndTime = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $bookingDate->format('Y-m-d') . ' ' . $endTimeString
                );
            } else {
                $bookingEndTime = Carbon::parse($endTimeString);
            }
            if ($currentTime->gt($bookingEndTime)) {
                return response()->json([
                    'message' => 'Cannot check in after your booking end time: ' . $bookingEndTime->format('Y-m-d H:i')
                ], 422);
            }
            // if (!$bookingDate->isToday()) {
            //     return response()->json([
            //         'message' => 'You can only check in on the day of your booking'
            //     ], 422);
            // }

            $checkinTime = $currentTime;
            $checkinTime = $currentTime;

            $checkin = VolunteerCheckin::create([
                'booking_id' => $booking->id,
                'checkin_time' => $checkinTime,
                'status' => 'checked_in'
            ]);

            $booking->update(['status' => 'checked_in']);

            $user->increment('points', 10);

            if ($user->strikes > 0) {
                $user->update(['strikes' => 0]);
            }

            Log::info("Volunteer checked in", [
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'checkin_id' => $checkin->id
            ]);

            return response()->json([
                'redirect' => route('volunteer.checkin.success', $checkin->id),
                'message' => 'Check-in successful! +10 points awarded'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Booking not found or you do not have permission to check in'
            ], 404);
        } catch (\Exception $e) {
            Log::error("Check-in failed: " . $e->getMessage(), [
                'user_id' => auth()->id() ?? null,
                'booking_code' => $request->booking_code ?? null,
                'booking_date' => $booking->booking_date ?? null,
                'end_time' => $booking->end_time ?? null
            ]);

            return response()->json([
                'message' => 'An error occurred during check-in. Please try again.'
            ], 500);
        }
    }


    public function showCheckinSuccess($checkinId)
    {
        $checkin = VolunteerCheckin::with('booking.ngo')
            ->whereHas('booking', function ($query) {
                $query->where('volunteer_id', auth()->id());
            })
            ->findOrFail($checkinId);

        return view('roles.volunteer.pages.checkin.success', compact('checkin'));
    }

    public function showCheckoutForm($checkinId)
    {
        $checkin = VolunteerCheckin::with('booking.ngo')
            ->whereHas('booking', function ($query) {
                $query->where('volunteer_id', auth()->id());
            })
            ->findOrFail($checkinId);

        return view('roles.volunteer.pages.checkout.form', compact('checkin'));
    }

    public function processCheckout(Request $request, $checkinId)
    {
        $request->validate([
            'lunch_duration' => 'required|integer|min:0',
        ]);

        $checkin = VolunteerCheckin::with('booking')
            ->whereHas('booking', function ($query) {
                $query->where('volunteer_id', auth()->id());
            })
            ->findOrFail($checkinId);

        $existingRequest = VolunteerCheckoutRequest::where('checkin_id', $checkin->id)
            ->where('volunteer_id', auth()->id())
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status === 'rejected') {
                $existingRequest->update([
                    'checkout_time' => now(),
                    'lunch_duration' => $request->lunch_duration,
                    'notes' => $request->notes,
                    'status' => 'pending'
                ]);
            } else {
                return redirect()->route('volunteer.checkout.requested', $checkin->id)
                    ->with('message', 'You already have a checkout request for this check-in.');
            }
        } else {
            VolunteerCheckoutRequest::create([
                'checkin_id' => $checkin->id,
                'volunteer_id' => auth()->id(),
                'checkout_time' => now(),
                'lunch_duration' => $request->lunch_duration,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);
        }

        return redirect()->route('volunteer.checkout.requested', $checkin->id);
    }


    public function showCheckoutRequested($checkinId)
    {
        $checkin = VolunteerCheckin::with(['booking.ngo', 'checkoutRequest'])
            ->whereHas('booking', function ($query) {
                $query->where('volunteer_id', auth()->id());
            })
            ->findOrFail($checkinId);

        return view('roles.volunteer.pages.checkout.requested', compact('checkin'));
    }

    public function showCheckoutSuccess($checkinId)
    {
        $checkin = VolunteerCheckin::with('booking.ngo')
            ->whereHas('booking', function ($query) {
                $query->where('volunteer_id', auth()->id());
            })
            ->findOrFail($checkinId);

        return view('roles.volunteer.pages.checkout.success', compact('checkin'));
    }
}
