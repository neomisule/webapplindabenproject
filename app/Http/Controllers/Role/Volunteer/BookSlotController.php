<?php

namespace App\Http\Controllers\Role\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use App\Models\VolunteerBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookSlotController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isStaff = $user->hasRole('staff');

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
            ->where('for_staff', 0)
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
            ->where('for_staff', 0)
            ->select('date')
            ->distinct()
            ->pluck('date')
            ->map(function ($date) {
                return date('Y-m-d', strtotime($date));
            })
            ->toArray();

        $slots = $ngos->map(function ($ngo) use ($isStaff) {
            $bookedCount = VolunteerBooking::where('ngo_id', $ngo->id)
                ->whereIn('status', ['booked', 'checked_in'])
                ->count();

            $availableSlots = max(0, $ngo->volunteers_needed - $bookedCount);

            if ($availableSlots <= 0 && !$isStaff) {
                return null;
            }

            $isBooked = VolunteerBooking::where('ngo_id', $ngo->id)
                ->where('volunteer_id', auth()->id())
                ->whereIn('status', ['booked', 'checked_in'])
                ->exists();

            $isRecurring = !empty($ngo->recurring_group_id);

            return [
                'id' => $ngo->id,
                'organization' => $ngo->name,
                'role' => $ngo->role,
                'program' => $ngo->program,
                'address' => $ngo->address ?? 'Address not specified',
                'time' => $this->formatTimeRange($ngo->start_time, $ngo->end_time),
                'date' => $this->formatDate($ngo->date),
                'status' => "$availableSlots/{$ngo->volunteers_needed}",
                'available_slots' => $availableSlots,
                'is_booked' => $isBooked,
                'total_slots' => $ngo->volunteers_needed,
                'allow_partial' => $ngo->allow_partial,
                'start_time' => $ngo->start_time,
                'end_time' => $ngo->end_time,
                'min_hours_per_volunteer' => $ngo->min_hours_per_volunteer,
                'is_recurring' => $isRecurring,
                'recurring_group_id' => $ngo->recurring_group_id,
                'for_staff' => $ngo->for_staff
            ];
        })->filter();

        return view('roles.volunteer.pages.slot.index', compact('slots', 'datesWithSlots', 'isStaff'));
    }

    public function book(Request $request)
    {
        $ngo = Ngo::findOrFail($request->slot_id);
        $user = auth()->user();
        $isStaff = $user->hasRole('staff');

        if ($isStaff && $ngo->recurring_group_id && $request->has('recurring_duration')) {
            return $this->processRecurringBooking($request, $ngo, $user);
        }

        $existingBooking = VolunteerBooking::where('ngo_id', $ngo->id)
            ->where('volunteer_id', $user->id)
            ->whereIn('status', ['booked', 'checked_in'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'You have already booked this slot!'
            ]);
        }

        $isPartial = $request->booking_type === 'custom_time';

        $startTime = $isPartial
            ? Carbon::parse($request->custom_start_time)
            : Carbon::parse($ngo->start_time);

        $endTime = $isPartial
            ? Carbon::parse($request->custom_end_time)
            : Carbon::parse($ngo->end_time);

        $ngoStart = Carbon::parse($ngo->start_time);
        $ngoEnd = Carbon::parse($ngo->end_time);

        if ($startTime < $ngoStart || $endTime > $ngoEnd) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Selected time must be within NGO slot time'
            ]);
        }

        $bookedCount = VolunteerBooking::where('ngo_id', $ngo->id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhereBetween('end_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime->format('H:i:s'))
                            ->where('end_time', '>=', $endTime->format('H:i:s'));
                    });
            })
            ->whereIn('status', ['booked', 'checked_in'])
            ->count();

        if ($bookedCount >= $ngo->volunteers_needed && !$isStaff) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'No available slots left for selected time!'
            ]);
        }

        $bookingData = [
            'volunteer_id' => $user->id,
            'ngo_id' => $ngo->id,
            'booking_code' => ($isStaff ? 'STAFF-' : 'VOL-') . strtoupper(Str::random(6)) . '-' . date('md'),
            'booking_date' => $ngo->date,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'status' => 'booked',
            'is_partial' => $request->is_partial,
            'is_staff_booking' => $isStaff,
        ];

        // Create the booking
        $booking = VolunteerBooking::create($bookingData);

        // Assign tasks if the NGO has tasks
        $this->assignTasksToVolunteer($ngo, $user, $booking);

        return redirect()->route('volunteer.bookings')->with('toast', [
            'type' => 'success',
            'message' => 'Slot booked successfully!'
        ]);
    }

    private function assignTasksToVolunteer(Ngo $ngo, User $user, VolunteerBooking $booking)
    {
        $tasks = $ngo->tasks()->where('status', '!=', 'completed')->get();

        foreach ($tasks as $task) {
            $existingAssignment = TaskAssignment::where('task_id', $task->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$existingAssignment) {
                TaskAssignment::create([
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                    'assigned_by' => auth()->id(),
                    'status' => 'pending',
                    'volunteer_booking_id' => $booking->id
                ]);
            }
        }
    }

    protected function processRecurringBooking($request, $mainNgo, $user)
    {
        $duration = $request->recurring_duration;
        $startDate = Carbon::parse($mainNgo->date);
        $endDate = $this->calculateEndDate($startDate, $duration);

        $recurringEvents = Ngo::where('recurring_group_id', $mainNgo->recurring_group_id)
            ->where('date', '>=', $startDate->format('Y-m-d'))
            ->where('date', '<=', $endDate->format('Y-m-d'))
            ->orderBy('date')
            ->get();

        $bookingsCreated = 0;

        foreach ($recurringEvents as $event) {
            if (
                Carbon::parse($event->date)->lt(now()) &&
                Carbon::parse($event->end_time)->lt(now()->format('H:i:s'))
            ) {
                continue;
            }

            $existingBooking = VolunteerBooking::where('ngo_id', $event->id)
                ->where('volunteer_id', $user->id)
                ->whereIn('status', ['booked', 'checked_in'])
                ->first();

            if ($existingBooking) {
                continue;
            }

            $bookedCount = VolunteerBooking::where('ngo_id', $event->id)
                ->whereIn('status', ['booked', 'checked_in'])
                ->count();

            if ($bookedCount >= $event->volunteers_needed) {
                continue;
            }

            VolunteerBooking::create([
                'volunteer_id' => $user->id,
                'ngo_id' => $event->id,
                'booking_code' => 'STAFF-' . strtoupper(Str::random(6)) . '-' . date('md'),
                'booking_date' => $event->date,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'status' => 'booked',
                'is_partial' => false,
                'is_staff_booking' => true,
                'is_recurring_booking' => true,
                'recurring_group_id' => $mainNgo->recurring_group_id,
            ]);

            $bookingsCreated++;
        }

        if ($bookingsCreated === 0) {
            return redirect()->back()->with('toast', [
                'type' => 'info',
                'message' => 'You already have bookings for all occurrences in this period'
            ]);
        }

        return redirect()->route('volunteer.bookings')->with('toast', [
            'type' => 'success',
            'message' => "Successfully booked $bookingsCreated recurring slots!"
        ]);
    }

    protected function calculateEndDate(Carbon $startDate, string $duration): Carbon
    {
        return match ($duration) {
            '1_month' => $startDate->copy()->addMonth(),
            '2_months' => $startDate->copy()->addMonths(2),
            '3_months' => $startDate->copy()->addMonths(3),
            default => $startDate->copy()->addMonth(),
        };
    }

    protected function formatTimeRange($start, $end)
    {
        return date('h:i A', strtotime($start)) . ' - ' . date('h:i A', strtotime($end));
    }

    protected function formatDate($date)
    {
        return date('d M Y', strtotime($date));
    }
}
