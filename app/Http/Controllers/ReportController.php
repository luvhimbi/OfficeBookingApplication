<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invite;
use App\Models\Booking;   // If you don't have booking remove it
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function userReports(Request $request)
    {
        $query = User::query();

        // Apply role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Apply date range filter
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Paginate
        $recentUsers = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $employees = User::where('role', 'employee')->count();
        $activeToday = User::whereDate('created_at', now()->toDateString())->count();

        // Chart data (example: users joined per day last 7 days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartLabels[] = $date;
            $chartData[] = User::whereDate('created_at', $date)->count();
        }

        return view('admin.reports.users', compact(
            'recentUsers', 'totalUsers', 'admins', 'employees', 'activeToday', 'chartLabels', 'chartData'
        ));

    }

    public function inviteReports(Request $request)
    {
        $query = Invite::query();

        // Filter by used/unused status
        if ($request->filled('status')) {
            if ($request->status === 'used') {
                $query->where('used', true);
            } elseif ($request->status === 'unused') {
                $query->where('used', false);
            }
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Paginate
        $recentInvites = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $totalInvites = Invite::count();
        $usedInvites = Invite::where('used', true)->count();
        $unusedInvites = Invite::where('used', false)->count();
        $todayInvites = Invite::whereDate('created_at', now()->toDateString())->count();

        // Chart data: invites sent per day (last 7 days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartLabels[] = $date;
            $chartData[] = Invite::whereDate('created_at', $date)->count();
        }

        return view('admin.reports.invites', compact(
            'recentInvites', 'totalInvites', 'usedInvites', 'unusedInvites', 'todayInvites', 'chartLabels', 'chartData'
        ));
    }

    public function bookings()
    {
        // Remove if not using bookings
        $totalBookings = Booking::count();
        $upcoming = Booking::where('booking_date', '>=', Carbon::today())->count();
        $cancelled = Booking::where('status', 'cancelled')->count();
        $recentBookings = Booking::latest()->take(10)->get();

        return view('admin.reports.bookings', compact(
            'totalBookings','upcoming','cancelled','recentBookings'
        ));
    }
}
