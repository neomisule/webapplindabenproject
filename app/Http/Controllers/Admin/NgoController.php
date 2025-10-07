<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use App\Models\Role;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use App\Models\VolunteerBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class NgoController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::where('name', 'volunteer')->first();
        $members = User::whereHas('roles', function ($query) use ($role) {
            $query->where('role_id', $role->id);
        })
            ->with(['roles'])
            ->latest()
            ->get();

        $query = Ngo::query()->orderBy('date', 'asc');
        $now = Carbon::now();
        if ($request->has('date_range')) {
            $dateRange = $request->input('date_range');
            $now = Carbon::now();
            $query->whereDate('date', '>=', $now->toDateString());

            switch ($dateRange) {
                case 'today':
                    $query->whereDate('date', $now->toDateString());
                    break;

                case 'tomorrow':
                    $query->whereDate('date', $now->addDay()->toDateString());
                    break;

                case 'this_week':
                    $query->whereBetween('date', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;

                case 'next_week':
                    $query->whereBetween('date', [
                        $now->addWeek()->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;

                case 'this_month':
                    $query->whereBetween('date', [
                        $now->startOfMonth()->toDateString(),
                        $now->endOfMonth()->toDateString()
                    ]);
                    break;

                case 'next_month':
                    $query->whereBetween('date', [
                        $now->addMonth()->startOfMonth()->toDateString(),
                        $now->endOfMonth()->toDateString()
                    ]);
                    break;

                case 'custom':
                    if ($request->has(['start_date', 'end_date'])) {
                        $query->whereBetween('date', [
                            $request->input('start_date'),
                            $request->input('end_date')
                        ]);
                    }
                    break;
            }
        }
        if ($request->has('status_filter')) {
            $statusFilter = $request->input('status_filter');
            if ($statusFilter == 'complete') {
                $query->whereDate('date', '<', $now->toDateString());
            } elseif ($statusFilter == 'upcoming') {
                $query->whereDate('date', '>=', $now->toDateString());
            }
        } elseif (!$request->has('date_range')) {
            $query->whereDate('date', '>=', $now->toDateString());
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('program', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%")
                    ->orWhere('role', 'like', "%$search%");
            });
        }

        $ngos = $query->paginate(20)->withQueryString();
        $tasks = Task::where('status', '!=', 'completed')->latest()->get();
        return view('admin.pages.ngos.index', compact('ngos', 'tasks', 'members'));
    }

    public function create()
    {
        $staffRole = Role::where('name', 'staff')->first();
        $staffMembers = User::whereHas('roles', function ($query) use ($staffRole) {
            $query->where('role_id', $staffRole->id);
        })
            ->with(['roles'])
            ->latest()
            ->get();

        return view('admin.pages.ngos.create', compact('staffMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'volunteers_needed' => 'required|integer|min:1',
            'is_recurring' => 'sometimes|boolean',
            'recurrence_pattern' => 'required_if:is_recurring,true|in:daily,weekly,monthly',
            'recurrence_days' => 'required_if:recurrence_pattern,weekly|array',
            'recurrence_days.*' => 'integer|min:1|max:7',
            'recurrence_duration' => 'required_if:is_recurring,true|in:3_months,6_months,1_year',
            'for_staff' => 'sometimes|boolean',
            'staff_ids' => 'sometimes|array',
            'staff_ids.*' => 'exists:users,id',
            'role' => 'required|string|max:255',
            'program' => 'required|string|max:255',
        ]);

        $eventsCreated = 0;
        $startDate = Carbon::parse($validated['date']);

        $isRecurring = $request->boolean('is_recurring');
        $recurringGroupId = $isRecurring ? Str::uuid()->toString() : null;

        if ($isRecurring) {
            $endDate = $this->calculateEndDate($startDate, $validated['recurrence_duration']);

            switch ($validated['recurrence_pattern']) {
                case 'daily':
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        $this->createEvent($validated, $date->toDateString(), $recurringGroupId);
                        $eventsCreated++;
                    }
                    break;

                case 'weekly':
                    $daysOfWeek = $validated['recurrence_days'];
                    $currentWeek = $startDate->copy()->startOfWeek();
                    while ($currentWeek->lte($endDate)) {
                        foreach ($daysOfWeek as $day) {
                            $eventDate = $currentWeek->copy()->addDays($day - 1);
                            if ($eventDate->between($startDate, $endDate)) {
                                $this->createEvent($validated, $eventDate->toDateString(), $recurringGroupId);
                                $eventsCreated++;
                            }
                        }
                        $currentWeek->addWeek();
                    }
                    break;

                case 'monthly':
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
                        $this->createEvent($validated, $date->toDateString(), $recurringGroupId);
                        $eventsCreated++;
                    }
                    break;
            }
        } else {
            $this->createEvent($validated, $validated['date'], null);
            $eventsCreated = 1;
        }

        return redirect()->route('admin.ngos.index')->with([
            'toast' => [
                'type' => 'success',
                'title' => 'Success',
                'message' => "Created $eventsCreated events successfully!"
            ]
        ]);
    }

    private function createEvent(array $data, string $date, ?string $recurringGroupId = null)
    {
        $ngo = Ngo::create([
            'recurring_group_id' => $recurringGroupId,
            'name' => $data['name'],
            'address' => $data['address'],
            'date' => $date,
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'volunteers_needed' => $data['volunteers_needed'],
            'status' => 1,
            'for_staff' => $data['for_staff'] ?? false,
            'allow_partial' => true,
            'min_hours_per_volunteer' => 1,
            'role' => $data['role'],
            'program' => $data['program'],
        ]);

        if (!empty($data['staff_ids'])) {
            $this->createStaffBookings($data['staff_ids'], $ngo);
        }

        return $ngo;
    }

    public function edit($id)
    {
        $ngo = Ngo::findOrFail($id);
        $staffRole = Role::where('name', 'staff')->first();
        $staffMembers = User::whereHas('roles', function ($query) use ($staffRole) {
            $query->where('role_id', $staffRole->id);
        })
            ->with(['roles'])
            ->latest()
            ->get();

        $assignedStaff = $ngo->staffBookings()->pluck('volunteer_id')->toArray();

        return view('admin.pages.ngos.edit', compact('ngo', 'staffMembers', 'assignedStaff'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'volunteers_needed' => 'required|integer|min:1',
            'staff_ids' => 'sometimes|array',
            'staff_ids.*' => 'exists:users,id',
            'role' => 'required|string|max:255',
            'program' => 'required|string|max:255',
        ]);

        $ngo = Ngo::findOrFail($id);
        $ngo->update($validated);

        // Sync staff bookings
        if (isset($validated['staff_ids'])) {
            $this->syncStaffBookings($validated['staff_ids'], $ngo);
        }

        return redirect()->route('admin.ngos.index')->with([
            'toast' => [
                'type' => 'success',
                'title' => 'Success',
                'message' => "Event updated successfully!"
            ]
        ]);
    }

    private function createStaffBookings(array $staffIds, Ngo $ngo)
    {
        foreach ($staffIds as $staffId) {
            VolunteerBooking::firstOrCreate([
                'volunteer_id' => $staffId,
                'ngo_id' => $ngo->id,
            ], [
                'booking_code' => 'STAFF-' . strtoupper(Str::random(6)) . '-' . date('md'),
                'booking_date' => $ngo->date,
                'start_time' => $ngo->start_time,
                'end_time' => $ngo->end_time,
                'status' => 'booked',
                'is_partial' => false,
                'is_staff_booking' => true,
            ]);
        }
    }

    private function syncStaffBookings(array $staffIds, Ngo $ngo)
    {
        $ngo->staffBookings()
            ->whereNotIn('volunteer_id', $staffIds)
            ->delete();

        $existingStaff = $ngo->staffBookings()->pluck('volunteer_id')->toArray();
        $newStaff = array_diff($staffIds, $existingStaff);

        if (!empty($newStaff)) {
            $this->createStaffBookings($newStaff, $ngo);
        }
    }

    private function calculateEndDate(Carbon $startDate, string $duration): Carbon
    {
        return match ($duration) {
            '6_months' => $startDate->copy()->addMonths(6),
            '1_year' => $startDate->copy()->addYear(),
            default => $startDate->copy()->addMonths(3),
        };
    }

    public function destroy($id)
    {
        $ngo = Ngo::findOrFail($id);

        try {
            $ngo->delete();

            return redirect()->route('admin.ngos.index')->with('toast', [
                'type' => 'success',
                'title' => 'Success!',
                'message' => 'NGO/Event deleted successfully!'
            ]);
        } catch (QueryException $ex) {
            if ($ex->getCode() == '23000' && strpos($ex->getMessage(), '1451') !== false) {
                return redirect()->route('admin.ngos.index')->with('toast', [
                    'type' => 'error',
                    'title' => 'Deletion Failed',
                    'message' => 'Cannot delete this NGO/Event.'
                ]);
            }

            return redirect()->route('admin.ngos.index')->with('toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'An unexpected error occurred while deleting.'
            ]);
        }
    }

    public function toggleStatus(Request $request)
    {
        $ngo = Ngo::find($request->id);
        if (!$ngo) {
            return response()->json(['success' => false, 'message' => 'NGO not found']);
        }
        $ngo->status = $request->new_status;
        $ngo->save();

        return redirect()->route('admin.ngos.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Status updated successfully!'
        ]);
    }

    public function bookStaff(Request $request)
    {
        $request->validate([
            'ngo_id' => 'required|exists:ngos,id',
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:users,id',
            'is_recurring' => 'sometimes|boolean',
            'recurrence_duration' => 'required_if:is_recurring,true|in:3_months,6_months,1_year',
        ]);

        $mainNgo = Ngo::findOrFail($request->ngo_id);
        $staffIds = $request->staff_ids;
        $eventsBooked = 0;

        if ($request->is_recurring) {
            $startDate = Carbon::parse($mainNgo->date);
            $endDate = $this->calculateEndDate($startDate, $request->recurrence_duration);

            $recurringEvents = Ngo::where('recurring_group_id', $mainNgo->recurring_group_id)
                ->where('date', '>=', Carbon::now())
                ->orderBy('date')
                ->get();

            foreach ($recurringEvents as $event) {
                $this->createStaffBookings($staffIds, $event);
                $eventsBooked++;
            }
        } else {
            $this->createStaffBookings($staffIds, $mainNgo);
            $eventsBooked = 1;
        }

        return redirect()->back()->with([
            'toast' => [
                'type' => 'success',
                'title' => 'Success',
                'message' => "Staff booked for $eventsBooked events successfully!"
            ]
        ]);
    }

    public function assignTask(Request $request)
    {
        $request->validate([
            'ngo_id' => 'required|exists:ngos,id',
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id'
        ]);

        try {
            $ngo = Ngo::findOrFail($request->ngo_id);

            // Sync tasks with the NGO using the pivot table
            $ngo->tasks()->syncWithoutDetaching($request->task_ids);

            return response()->json([
                'success' => true,
                'message' => 'Tasks assigned to NGO successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error assigning tasks: ' . $e->getMessage()
            ]);
        }
    }

    public function showTasks(Ngo $ngo)
    {
        $tasks = $ngo->tasks()
            ->with(['assignments.user', 'assignments' => function ($query) {
                $query->orderBy('status')->orderBy('created_at', 'desc');
            }])
            ->orderBy('priority', 'desc')
            ->get();

        $availableUsers = User::whereDoesntHave('taskAssignments', function ($query) use ($ngo) {
            $query->whereHas('task', function ($q) use ($ngo) {
                $q->whereHas('ngos', function ($q2) use ($ngo) {
                    $q2->where('ngos.id', $ngo->id);
                });
            });
        })
            ->where('status', 'active')
            ->get();

        $alltask = Task::whereDoesntHave('ngos', function ($query) use ($ngo) {
            $query->where('ngos.id', $ngo->id);
        })
            ->get();

        $userStats = User::select('users.id', 'users.name', 'users.email')
            ->leftJoin('task_assignments', 'users.id', '=', 'task_assignments.user_id')
            ->leftJoin('tasks', 'task_assignments.task_id', '=', 'tasks.id')
            ->leftJoin('ngo_task', 'tasks.id', '=', 'ngo_task.task_id')
            ->where('ngo_task.ngo_id', $ngo->id)
            ->selectRaw('COUNT(tasks.id) as total_tasks')
            ->selectRaw('SUM(CASE WHEN task_assignments.status = "pending" THEN 1 ELSE 0 END) as pending_tasks')
            ->selectRaw('SUM(CASE WHEN task_assignments.status = "in_progress" THEN 1 ELSE 0 END) as in_progress_tasks')
            ->selectRaw('SUM(CASE WHEN task_assignments.status = "completed" THEN 1 ELSE 0 END) as completed_tasks')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_tasks', 'desc')
            ->get();

        return view('admin.pages.ngos.tasks', compact('ngo', 'tasks', 'availableUsers', 'alltask', 'userStats'));
    }

    public function assignTaskToUser(Request $request, Ngo $ngo)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $assignedCount = 0;
        $alreadyAssigned = 0;

        foreach ($request->user_ids as $userId) {
            // Check if assignment already exists
            $exists = TaskAssignment::where('task_id', $request->task_id)
                ->where('user_id', $userId)
                ->exists();

            if (!$exists) {
                TaskAssignment::create([
                    'task_id' => $request->task_id,
                    'user_id' => $userId,
                    'assigned_by' => auth()->id(),
                    'status' => 'pending'
                ]);
                $assignedCount++;
            } else {
                $alreadyAssigned++;
            }
        }

        $message = "Assigned to $assignedCount users.";
        if ($alreadyAssigned > 0) {
            $message .= " $alreadyAssigned users were already assigned.";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function removeTaskAssignment(TaskAssignment $assignment)
    {
        $assignment->delete();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Assignment removed successfully'
        ]);
    }

    // public function assignTask(Request $request)
    // {
    //     $request->validate([
    //         'ngo_id' => 'required|exists:ngos,id',
    //         'task_ids' => 'required|array',
    //         'task_ids.*' => 'exists:tasks,id'
    //     ]);

    //     Task::whereIn('id', $request->task_ids)
    //         ->update(['ngo_id' => $request->ngo_id]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Tasks assigned successfully to NGO'
    //     ]);
    // }
}
