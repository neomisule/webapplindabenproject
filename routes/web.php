<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
     return view('welcome');
})->name('home');
    Route::get('/select', [AuthController::class, 'showNgoSelection'])->name('volunteers.select');
    Route::get('/list', [AuthController::class, 'showVolunteerList'])->name('volunteers.list');


require base_path('routes/admin.php');
require base_path('routes/role.php');
