<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Role\ProfileController;
use App\Http\Controllers\Role\Volunteer\BookSlotController;
use App\Http\Controllers\Role\Volunteer\DashboardController;
use App\Http\Controllers\Role\Staff\StaffDashboardController;
use App\Http\Controllers\Role\Staff\StaffSlotController;
use App\Http\Controllers\Role\User\UserDashboardController;
use App\Http\Controllers\Role\Volunteer\BookingController;
use App\Http\Controllers\Role\Volunteer\CheckinController;
use Illuminate\Support\Facades\Route;

// Common Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('check.ban');

// User Specific
Route::get('/user/login', [AuthController::class, 'showUserLoginForm'])->name('user.login');

// Volunteer Registration
Route::get('/volunteer/register', [AuthController::class, 'showVolunteerRegisterForm'])->name('volunteer.register');
Route::post('/volunteer/register', [AuthController::class, 'volunteerRegister']);

// Volunteer Approval Flow
Route::get('/volunteer/approve/{token}', [AuthController::class, 'approveFromEmail'])->name('volunteer.approve');
Route::get('/volunteer/set-password/{token}', [AuthController::class, 'showVolunteerPasswordSetup'])->name('volunteer.password.setup');
Route::post('/volunteer/set-password/{token}', [AuthController::class, 'setupVolunteerPassword']);

// User Registration
Route::get('/user/register', [AuthController::class, 'showUserRegisterForm'])->name('user.register');
Route::post('/user/register', [AuthController::class, 'userRegister']);

// Common Authenticated Routes
Route::get('/registration-success', [AuthController::class, 'showRegistrationSuccess'])->name('registration.success');
Route::middleware(['auth', 'check.ban'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Routes (accessible by all roles)
    Route::get('/profile', [ProfileController::class, 'index'])->name('role.profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('role.profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('role.profile.password');
});

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


// Volunteer Routes
Route::prefix('volunteer')->middleware(['auth', 'check.ban', 'volunteer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('volunteer.dashboard');

    Route::prefix('book-slot')->group(function () {
        Route::get('/', [BookSlotController::class, 'index'])->name('volunteer.book-slot');
        Route::post('/book', [BookSlotController::class, 'book'])->name('volunteer.book-slot.book');
    });

    // Bookings Management
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('volunteer.bookings');
        Route::get('/{id}', [BookingController::class, 'show'])->name('volunteer.bookings.show');
        Route::delete('/{id}/cancel', [BookingController::class, 'cancel'])->name('volunteer.bookings.cancel');
    });

    // Checkin/Checkout Routes
    Route::prefix('checkin')->group(function () {
        Route::get('/', [CheckinController::class, 'showCheckinForm'])->name('volunteer.checkin');
        Route::post('/', [CheckinController::class, 'processCheckin'])->name('volunteer.checkin.process');
        Route::get('/success/{id}', [CheckinController::class, 'showCheckinSuccess'])->name('volunteer.checkin.success');
    });

    Route::prefix('checkout')->group(function () {
        Route::get('/{id}', [CheckinController::class, 'showCheckoutForm'])->name('volunteer.checkout');
        Route::post('/{id}', [CheckinController::class, 'processCheckout'])->name('volunteer.checkout.process');
        Route::get('/success/{id}', [CheckinController::class, 'showCheckoutSuccess'])->name('volunteer.checkout.success');
    });

    Route::get('/staff-slots', [StaffSlotController::class, 'index'])->name('volunteer.staff-slots');
});

// Staff Routes
Route::prefix('staff')->middleware(['auth', 'check.ban', 'staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
    Route::get('/become-volunteer', [AuthController::class, 'showBecomeVolunteerForm'])->name('become.volunteer');
    Route::post('/request-volunteer', [AuthController::class, 'requestBecomeVolunteer'])->name('staff.request.volunteer');
});

// User Routes
Route::prefix('user')->middleware(['auth', 'check.ban', 'user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/become-volunteer', [AuthController::class, 'showBecomeVolunteerForm'])->name('user.become.volunteer');
    Route::post('/request-volunteer', [AuthController::class, 'requestBecomeVolunteer'])->name('user.request.volunteer');
});

// Volunteer Approval Success
Route::get('/volunteer/approval-success', function () {
    return view('roles.auth.approval-success');
})->name('volunteer.approval.success');


// Approval routes
Route::get('/approve-staff-volunteer/{token}', [AuthController::class, 'approveStaffVolunteer'])->name('staff.approve-volunteer');
Route::get('/checkout/requested/{checkinId}', [CheckinController::class, 'showCheckoutRequested'])->name('volunteer.checkout.requested')->middleware('check.ban');
