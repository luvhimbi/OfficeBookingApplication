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
        $activeToday = User::whereDate('last_active_at', now()->toDateString())->count();

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
    public function bookingReports(array $filters = [], int $perPage = 10)
    {
        $query = Booking::query()
            ->with(['user','campus','building','floor','boardroom','desk']);

        // Filters
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'upcoming') {
                $query->where('date', '>=', now()->toDateString());
            } elseif ($filters['status'] === 'cancelled') {
                $query->where('status', 'cancelled');
            } else {
                $query->where('status', $filters['status']);
            }
        }

        if (!empty($filters['from'])) {
            $query->whereDate('date', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('date', '<=', $filters['to']);
        }

        // Paginate recent bookings
        $recentBookings = $query->orderBy('date', 'desc')->paginate($perPage);

        // Basic stats
        $totalBookings = Booking::count();
        $upcoming = Booking::where('date', '>=', now()->toDateString())->count();
        $cancelled = Booking::where('status', 'cancelled')->count();
        $todayBookings = Booking::whereDate('date', now()->toDateString())->count();

        // Chart: last 7 days
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $chartLabels[] = $day;
            $chartData[] = Booking::whereDate('date', $day)->count();
        }

        // Top campuses (top 5)
        $topCampuses = Booking::selectRaw('campus_id, COUNT(*) as total')
            ->whereNotNull('campus_id')
            ->groupBy('campus_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        // eager-load campus relationship on each Booking model instance (if present)
        $topCampuses->load('campus');

        // Top space types (desk vs boardroom)
        $topSpaceTypes = Booking::selectRaw('space_type, COUNT(*) AS total')
            ->groupBy('space_type')
            ->orderByDesc('total')
            ->get();



        // Monthly trends (year-month, last 12 months)
        $monthlyTrends = Booking::selectRaw("
            EXTRACT(YEAR FROM date) AS year,
            EXTRACT(MONTH FROM date) AS month,
            COUNT(*) AS total
        ")
            ->groupByRaw('EXTRACT(YEAR FROM date), EXTRACT(MONTH FROM date)')
            ->orderByRaw('year ASC, month ASC')
            ->get()
            ->map(function ($row) {
                // normalize label to YYYY-MM for charts
                $y = (int) $row->year;
                $m = str_pad((int)$row->month, 2, '0', STR_PAD_LEFT);
                $row->month_label = "{$y}-{$m}";
                return $row;
            });

        // User ranking (top 10) — ensure the collection items are Booking model-like so we can eager load user
        $userRanking = Booking::selectRaw('user_id, COUNT(*) AS total')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        // Eager-load the user relation for each aggregated row so blade can do $u->user->firstname
        // This works because the returned items are Booking model instances (with an extra "total" attribute).
        $userRanking->load('user');

        // Return everything Blade expects
        return compact(
            'recentBookings',
            'totalBookings',
            'upcoming',
            'cancelled',
            'todayBookings',
            'chartLabels',
            'chartData',
            'topCampuses',
            'topSpaceTypes',
            'monthlyTrends',
            'userRanking'
        );
    }



}
