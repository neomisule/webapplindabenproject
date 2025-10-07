<?php

namespace App\Http\Controllers\Role\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ngo;
use App\Models\VolunteerBooking;

class StaffSlotController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $currentDate = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        $query = Ngo::where('status', 1)
        ->where(function ($q) use ($currentDate, $currentTime) {
            $q->where('date', '>', $currentDate)
                ->orWhere(function ($q2) use ($currentDate, $currentTime) {
                    $q2->where('date', $currentDate)
                        ->where('end_time', '>', $currentTime);
                });
        })
        ->where('for_staff', true)
        ->orderBy('date', 'asc')
        ->orderBy('start_time', 'asc');

        if ($request->has('filter_date')) {
            $query->where('date', $request->filter_date);
        }

        $ngos = $query->get();
        $datesWithSlots = Ngo::where('status', 1)
            ->where(function ($q) use ($currentDate, $currentTime) {
                $q->where('date', '>', $currentDate)
                    ->orWhere(function ($q2) use ($currentDate, $currentTime) {
                        $q2->where('date', $currentDate)
                            ->where('end_time', '>', $currentTime);
                    });
            })
            ->select('date')
            ->distinct()
            ->pluck('date')
            ->map(function ($date) {
                return date('Y-m-d', strtotime($date));
            })
             ->where('for_staff', true)

            ->toArray();

        $slots = $ngos->map(function ($ngo) {
            $bookedCount = VolunteerBooking::where('ngo_id', $ngo->id)
                ->whereIn('status', ['booked', 'checked_in'])
                ->count();

            $availableSlots = max(0, $ngo->volunteers_needed - $bookedCount);

            $isBooked = VolunteerBooking::where('ngo_id', $ngo->id)
                ->where('volunteer_id', auth()->id())
                ->whereIn('status', ['booked', 'checked_in'])
                ->exists();

            return [
                'id' => $ngo->id,
                'organization' => $ngo->name,
                'address' => $ngo->address ?? 'Address not specified',
                'time' => date('h:i A', strtotime($ngo->start_time)) . ' - ' . date('h:i A', strtotime($ngo->end_time)),
                'date' => date('d M Y', strtotime($ngo->date)),
                'status' => $availableSlots . '/' . $ngo->volunteers_needed,
                'available_slots' => $availableSlots,
                'is_booked' => $isBooked,
                'total_slots' => $ngo->volunteers_needed
            ];
        });

        return view('roles.volunteer.pages.slot.staff-index', compact('slots','datesWithSlots'));
    }
}
