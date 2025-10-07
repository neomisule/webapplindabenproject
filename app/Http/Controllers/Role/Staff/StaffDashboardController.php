<?php

namespace App\Http\Controllers\Role\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index(){
        return view('roles.staff.dashboard');
    }
}
