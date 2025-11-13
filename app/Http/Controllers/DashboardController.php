<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all bookings for logged-in user
        $bookings = Booking::where('user_id', $user->id)
            ->with(['campus', 'building', 'floor'])
            ->orderBy('start_time', 'desc')
            ->get();

        // Separate upcoming and past bookings
        $upcomingBookings = $bookings->filter(function ($booking) {
            return Carbon::parse($booking->end_time)->isFuture();
        });

        $pastBookings = $bookings->filter(function ($booking) {
            return Carbon::parse($booking->end_time)->isPast();
        });

        // Suggested favorite spaces with count
        $favoriteSpaces = Booking::select('campus_id', 'building_id', 'floor_id', 'space_type', DB::raw('COUNT(*) as booked_count'))
            ->where('user_id', $user->id)
            ->groupBy('campus_id', 'building_id', 'floor_id', 'space_type')
            ->orderByDesc('booked_count')
            ->limit(5)
            ->with(['campus', 'building', 'floor'])
            ->get();

        return view('employee.dashboard', compact('user', 'upcomingBookings', 'pastBookings', 'favoriteSpaces'));
    }
}
