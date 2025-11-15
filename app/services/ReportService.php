<?php

namespace App\Services;

use App\Models\User;
use App\Models\Invite;
use App\Models\Booking; // Remove if not used
use Carbon\Carbon;

class ReportService
{
    /**
     * Get user report data.
     */
    public function userReports(array $filters = [], int $perPage = 10)
    {
        $query = User::query();

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        $recentUsers = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $employees = User::where('role', 'employee')->count();
        $activeToday = User::whereDate('created_at', now()->toDateString())->count();

        // Chart: users joined per day last 7 days
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartLabels[] = $date;
            $chartData[] = User::whereDate('created_at', $date)->count();
        }

        return compact('recentUsers','totalUsers','admins','employees','activeToday','chartLabels','chartData');
    }

    /**
     * Get invite report data.
     */
    public function inviteReports(array $filters = [], int $perPage = 10)
    {
        $query = Invite::query();

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'used') {
                $query->where('used', true);
            } elseif ($filters['status'] === 'unused') {
                $query->where('used', false);
            }
        }

        if (!empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        $recentInvites = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $totalInvites = Invite::count();
        $usedInvites = Invite::where('used', true)->count();
        $unusedInvites = Invite::where('used', false)->count();
        $todayInvites = Invite::whereDate('created_at', now()->toDateString())->count();

        // Chart: invites sent per day last 7 days
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartLabels[] = $date;
            $chartData[] = Invite::whereDate('created_at', $date)->count();
        }

        return compact('recentInvites','totalInvites','usedInvites','unusedInvites','todayInvites','chartLabels','chartData');
    }

    /**
     * Get booking report data.
     */
    public function bookingReports()
    {
        $totalBookings = Booking::count();
        $upcoming = Booking::where('booking_date', '>=', Carbon::today())->count();
        $cancelled = Booking::where('status', 'cancelled')->count();
        $recentBookings = Booking::latest()->take(10)->get();

        return compact('totalBookings','upcoming','cancelled','recentBookings');
    }
}
