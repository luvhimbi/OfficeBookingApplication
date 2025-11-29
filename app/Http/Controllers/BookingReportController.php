<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookingReportController
{
    public function exportPDF(Request $request)
    {
        $status = $request->input('status');
        $from = $request->input('from');
        $to = $request->input('to');

        $bookingsQuery = \App\Models\Booking::with(['user','campus','building','floor']);

        if ($status) {
            $bookingsQuery->where('status', $status);
        }
        if ($from) {
            $bookingsQuery->whereDate('date', '>=', $from);
        }
        if ($to) {
            $bookingsQuery->whereDate('date', '<=', $to);
        }

        $recentBookings = $bookingsQuery->orderBy('date','desc')->get();

        // Statistics
        $totalBookings = $bookingsQuery->count();
        $upcoming = $bookingsQuery->where('status','upcoming')->count();
        $cancelled = $bookingsQuery->where('status','cancelled')->count();
        $todayBookings = $bookingsQuery->whereDate('date', now()->toDateString())->count();

        // Chart Data for PDF (simple aggregated data)
        $dailyLabels = $recentBookings->pluck('date')->unique()->map(fn($d) => $d->format('Y-m-d'))->toArray();
        $dailyData = [];
        foreach ($dailyLabels as $label) {
            $dailyData[] = $recentBookings->where('date',$label)->count();
        }

        // Monthly trends
        $monthlyLabels = $recentBookings->groupBy(fn($b) => $b->date->format('F Y'))->keys()->toArray();
        $monthlyData = [];
        foreach ($monthlyLabels as $label) {
            $monthlyData[] = $recentBookings->filter(fn($b) => $b->date->format('F Y') === $label)->count();
        }

        // Top users
        $userRanking = $recentBookings->groupBy('user_id')->map(fn($b, $key) => [
            'user' => $b->first()->user,
            'total' => $b->count()
        ])->sortByDesc('total')->take(10);

        $pdf = Pdf::loadView('admin.reports.bookings_pdf', compact(
            'recentBookings','totalBookings','upcoming','cancelled','todayBookings',
            'status','from','to','dailyLabels','dailyData','monthlyLabels','monthlyData','userRanking'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('booking_report.pdf');
    }
}
