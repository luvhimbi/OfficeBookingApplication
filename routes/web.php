<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/password/reset', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/reset', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

// Show reset password form with token
Route::get('/password/reset/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');

// Handle password update
Route::post('/password/reset/update', [PasswordResetController::class, 'resetPassword'])->name('password.update');
// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('otp.resend');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/2fa-toggle', [AuthController::class, 'toggle2FA'])->name('profile.2fa.toggle');
});

Route::middleware(['auth'])->group(function () {

    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {

        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/users', [ReportController::class, 'userReports'])->name('users');
        Route::get('/invites', [ReportController::class, 'inviteReports'])->name('invites');
        Route::get('/bookings', [ReportController::class, 'bookings'])->name('bookings');

    });

         Route::get('/admin/users/invite', [InviteController::class, 'create'])->name('admin.invites.create');
         Route::post('/admin/users/invite', [InviteController::class, 'store'])->name('admin.invites.store');

         // User sets password after clicking invite link
         Route::get('/invite/accept/{token}', [InviteController::class, 'accept'])->name('invite.accept');
         Route::post('/invite/complete/{token}', [InviteController::class, 'complete'])->name('invite.complete');

// Manage invites list
         Route::get('/admin/invites', [InviteController::class, 'index'])
             ->name('admin.invites.index');

// Delete invite
         Route::delete('/admin/invites/{invite}', [InviteController::class, 'destroy'])
             ->name('admin.invites.destroy');


         Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    // Campus Management Routes (Admin Only)
    Route::resource('campuses', \App\Http\Controllers\CampusController::class)
        ->middleware(['auth']);

    Route::get('/bookings', [App\Http\Controllers\BookingController::class, 'index'])
        ->name('bookings.index');
    // Building Management Routes (Admin Only)
    Route::resource('buildings', \App\Http\Controllers\BuildingController::class)
        ->middleware(['auth']);

    // Floor Management Routes (Admin Only)
    Route::resource('floors', \App\Http\Controllers\FloorController::class)
        ->middleware(['auth']);

    // Boardroom Management Routes (Admin Only)
    Route::resource('boardrooms', \App\Http\Controllers\BoardroomController::class)
        ->middleware(['auth']);

    // Desk Management Routes (Admin Only)
    Route::resource('desks', \App\Http\Controllers\DeskController::class)
        ->middleware(['auth']);
         Route::resource('availabilities', AvailabilityController::class);


    // Ajax routes for dynamic dropdowns
    Route::get('/bookings/get-buildings/{campus}', [BookingController::class, 'getBuildings'])->name('bookings.getBuildings');
    Route::get('/bookings/get-floors/{building}', [BookingController::class, 'getFloors'])->name('bookings.getFloors');
    Route::get('/bookings/get-spaces/{floor}/{type}', [BookingController::class, 'getSpaces'])->name('bookings.getSpaces');
    //booking routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index'); // List of user's bookings
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create'); // Booking form
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store'); // Store booking
    Route::get('/bookings/calendar', [BookingController::class, 'calendarView'])->name('bookings.calendar');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    //notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');


    Route::get('/employee/dashboard',[DashboardController::class,'index'])->name('employee.dashboard');
     Route::get('admin/dashboard',[DashboardController::class,'adminIndex'])->name('admin.dashboard');

});



Route::get('/', function () {
    return view('welcome');
});
