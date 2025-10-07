<?php

use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\Admin\CheckoutRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\NgoController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VolunteerController;
use Illuminate\Support\Facades\Route;



Route::middleware(['web'])->group(function () {
    // Auth Routes
    Route::get('/admin/login', [LoginController::class, 'index'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::prefix('volunteers')->name('volunteers.')->group(function () {
        Route::get('/', [VolunteerController::class, 'index'])->name('index');
        Route::get('/create', [VolunteerController::class, 'create'])->name('create');
        Route::post('/', [VolunteerController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [VolunteerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VolunteerController::class, 'update'])->name('update');
        Route::delete('/{id}', [VolunteerController::class, 'destroy'])->name('destroy');
        Route::post('/toggleStatus', [VolunteerController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('{volunteer}/approve', [VolunteerController::class, 'approve'])->name('approve');
        Route::get('/{id}/bookings', [VolunteerController::class, 'bookings'])->name('bookings');
        Route::get('/{volunteer}/tasks', [VolunteerController::class, 'tasks'])->name('tasks');
        Route::get('/{volunteer}/book-slot', [VolunteerController::class, 'bookSlot'])->name('book-slot');
        Route::post('/{volunteer}/book-slot', [VolunteerController::class, 'storeBooking'])->name('store-booking');
        Route::patch('/{volunteer}/bookings/{booking}/cancel', [VolunteerController::class, 'cancelBooking'])->name('bookings.cancel');
    });


    // Users routes (without edit functionality)
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/{id}/add-volunteer', [UserController::class, 'addVolunteerRole'])->name('add-volunteer');
        Route::post('/{id}/remove-volunteer', [UserController::class, 'removeVolunteerRole'])->name('remove-volunteer');
        Route::post('/{id}/approve-request', [UserController::class, 'approveRequest'])->name('approve-request');
    });

    // Staff routes (with create/store but without approve)
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('/create', [StaffController::class, 'create'])->name('create');
        Route::post('/', [StaffController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [StaffController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StaffController::class, 'update'])->name('update');
        Route::delete('/{id}', [StaffController::class, 'destroy'])->name('destroy');
        Route::post('/toggleStatus', [StaffController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/{id}/add-volunteer', [StaffController::class, 'addVolunteerRole'])->name('add-volunteer');
        Route::post('/{id}/remove-volunteer', [StaffController::class, 'removeVolunteerRole'])->name('remove-volunteer');
        Route::post('/{token}/approve-request', [StaffController::class, 'approveRequest'])->name('approve-request');
    });

    Route::prefix('ngos')->name('ngos.')->group(function () {
        Route::get('/', [NgoController::class, 'index'])->name('index');
        Route::get('/create', [NgoController::class, 'create'])->name('create');
        Route::post('/', [NgoController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [NgoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NgoController::class, 'update'])->name('update');
        Route::delete('/{id}', [NgoController::class, 'destroy'])->name('destroy');
        Route::post('/toggleStatus', [NgoController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/book-staff', [NgoController::class, 'bookStaff'])->name('bookStaff');
        Route::post('/assign-task', [NgoController::class, 'assignTask'])->name('assignTask');
        Route::get('/{ngo}/tasks', [NgoController::class, 'showTasks'])->name('tasks');
        Route::post('/{ngo}/assign-task-to-user', [NgoController::class, 'assignTaskToUser'])->name('assignTaskToUser');
        Route::delete('/task-assignments/{assignment}', [NgoController::class, 'removeTaskAssignment'])->name('task-assignments.destroy');
    });

    // Task routes
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
        Route::post('/{task}/status', [TaskController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/{task}/assign', [TaskController::class, 'assign'])->name('assign');
        Route::delete('/{task}/unassign/{user}', [TaskController::class, 'unassign'])->name('unassign');
    });

    // Admin Booking Routes
    Route::prefix('bookings')->group(function () {
        Route::get('/export', [BookingsController::class, 'export'])->name('bookings.export');
        Route::get('/', [BookingsController::class, 'index'])->name('bookings.index');
        Route::get('/{id}', [BookingsController::class, 'show'])->name('bookings.show');
        Route::post('/{id}/update-status', [BookingsController::class, 'updateStatus'])->name('bookings.update-status');
    });

    Route::prefix('checkout-requests')->group(function () {
        Route::get('/', [CheckoutRequestController::class, 'index'])->name('checkout-requests.index');
        Route::post('/{id}/approve', [CheckoutRequestController::class, 'approve'])->name('checkout-requests.approve');
        Route::post('/{id}/reject', [CheckoutRequestController::class, 'reject'])->name('checkout-requests.reject');
    });
});
