<?php

namespace App\Http\Controllers;


use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class UserReportController
{
    public function exportPDF(Request $request)
    {
        // Get filters from request
        $role = $request->input('role');
        $from = $request->input('from');
        $to = $request->input('to');

        // Query users based on filters
        $usersQuery = \App\Models\User::query();

        if ($role) {
            $usersQuery->where('role', $role);
        }
        if ($from) {
            $usersQuery->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $usersQuery->whereDate('created_at', '<=', $to);
        }

        $recentUsers = $usersQuery->orderBy('created_at', 'desc')->get();

        // Statistics
        $totalUsers = $usersQuery->count();
        $admins = $usersQuery->where('role', 'admin')->count();
        $employees = $usersQuery->where('role', 'employee')->count();
        $activeToday = $usersQuery->whereDate('last_active_at', now()->toDateString())->count();

        // Chart data (optional: you can pre-calculate)
        $chartLabels = $recentUsers->pluck('created_at')->map(fn($d) => $d->format('Y-m-d'))->toArray();
        $chartData = $recentUsers->countBy(fn($user) => $user->created_at->format('Y-m-d'))->values()->toArray();

        // Generate PDF using a Blade view
        $pdf = Pdf::loadView('admin.reports.users_pdf', compact(
            'recentUsers', 'totalUsers', 'admins', 'employees', 'activeToday', 'chartLabels', 'chartData'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('user_report.pdf');
    }
}
