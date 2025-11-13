<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/password/reset', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/reset', [PasswordResetController::class, 'sendResetLink'])->name('password.email');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});
// Dashboards
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('Admin.dashboard');
    })->name('admin.dashboard');

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

    Route::get('/bookings/get-buildings/{campus}', [BookingController::class, 'getBuildings'])->name('bookings.getBuildings');
    Route::get('/bookings/get-floors/{building}', [BookingController::class, 'getFloors'])->name('bookings.getFloors');
    Route::get('/bookings/get-spaces/{floor}/{type}', [BookingController::class, 'getSpaces'])->name('bookings.getSpaces');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index'); // List of user's bookings
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create'); // Booking form
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store'); // Store booking
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');


    Route::get('/employee/dashboard',[DashboardController::class,'index'])->name('employee.dashboard');

});
Route::get('/', function () {
    return view('welcome');
});
