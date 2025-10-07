<?php

namespace App\Http\Controllers\Role\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(){
        return view('roles.user.dashboard');
    }
}
