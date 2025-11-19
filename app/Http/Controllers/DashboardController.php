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
            ->with(['campus', 'building', 'floor','desk','boardroom'])
            ->orderBy('start_time', 'desc')
            ->get();

        // Today’s date
        $today = Carbon::today();

        // Today's bookings
        $todaysBookings = $bookings->filter(function ($booking) use ($today) {
            return Carbon::parse($booking->date)->isSameDay($today);
        });

        // Separate upcoming and past bookings
        $upcomingBookings = $bookings->filter(function ($booking) {
            if ($booking->status === 'cancelled') {
                return false;
            }

            $start = Carbon::parse($booking->date)
                ->setTimeFromTimeString($booking->start_time);

            return $start->isFuture();
        });

// PAST BOOKINGS (status not cancelled + end time in past)
        $pastBookings = $bookings->filter(function ($booking) {
            if ($booking->status === 'cancelled') {
                return false;
            }

            $end = Carbon::parse($booking->date)
                ->setTimeFromTimeString($booking->end_time);

            return $end->isPast();
        });

        // Cancelled bookings only
        $cancelledBookings = $bookings->filter(function ($booking) {
            return $booking->status === 'cancelled';
        });

        $favoriteSpaces = Booking::query()
            ->select([
                'campus_id',
                'building_id',
                'floor_id',
                'space_type',
                'space_id',
                DB::raw('COUNT(*) as booked_count')
            ])
            ->where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->groupBy('campus_id', 'building_id', 'floor_id', 'space_type', 'space_id')
            ->havingRaw('COUNT(*) >= 2')
            ->orderByDesc('booked_count')
            ->limit(5)
            ->with(['campus', 'building', 'floor'])
            ->get();


        return view('employee.dashboard',
            compact(
                'user',
                'todaysBookings',
                'upcomingBookings',
                'pastBookings',
                'cancelledBookings',
                'favoriteSpaces'
            )
        );
    }


    public function adminIndex()
    {

        $bookingsCount = Booking::count();
        $usersCount = DB::table('users')->count();
        $campusesCount = DB::table('campuses')->count();
        $buildingsCount = DB::table('buildings')->count();
        $floorsCount = DB::table('floors')->count();
        $boardroomsCount = DB::table('boardrooms')->count();
        $desksCount = DB::table('desks')->count();
        return view('Admin.dashboard',compact('bookingsCount','usersCount','campusesCount','buildingsCount','floorsCount','boardroomsCount','desksCount'));
    }
}
