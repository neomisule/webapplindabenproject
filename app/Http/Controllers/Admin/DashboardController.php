<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use App\Models\Role;
use App\Models\User;
use App\Models\VolunteerBooking;
use App\Models\Task;
use App\Models\VolunteerCheckin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // User counts
        $staffCount = User::whereHas('roles', function($q) {
            $q->where('name', 'staff');
        })->count();

        $volunteerCount = User::whereHas('roles', function($q) {
            $q->where('name', 'volunteer');
        })->count();

        $userCount = User::whereHas('roles', function($q) {
            $q->where('name', 'user');
        })->count();

        // Slot and booking data
        $totalSlots = Ngo::count();
        $upcomingSlots = Ngo::where('date', '>=', Carbon::today())->count();
        $completedSlots = Ngo::where('date', '<', Carbon::today())->count();

        $totalBookings = VolunteerBooking::count();
        $upcomingBookings = VolunteerBooking::where('booking_date', '>=', Carbon::today())->count();
        $completedBookings = VolunteerBooking::where('booking_date', '<', Carbon::today())->count();

        // Task data
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();



        return view('admin.pages.dashboard', compact(
            'staffCount',
            'volunteerCount',
            'userCount',
            'totalSlots',
            'upcomingSlots',
            'completedSlots',
            'totalBookings',
            'upcomingBookings',
            'completedBookings',
            'totalTasks',
            'pendingTasks',
            'completedTasks',

        ));
    }
}
