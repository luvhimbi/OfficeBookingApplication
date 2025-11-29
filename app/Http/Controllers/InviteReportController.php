<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InviteReportController
{
    public function exportPDF(Request $request)
    {
        // Get filters
        $status = $request->input('status');
        $from = $request->input('from');
        $to = $request->input('to');

        // Query invites with filters
        $invitesQuery = \App\Models\Invite::query();

        if ($status === 'used') {
            $invitesQuery->where('used', true);
        } elseif ($status === 'unused') {
            $invitesQuery->where('used', false);
        }

        if ($from) {
            $invitesQuery->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $invitesQuery->whereDate('created_at', '<=', $to);
        }

        $recentInvites = $invitesQuery->orderBy('created_at', 'desc')->get();

        // Statistics
        $totalInvites = $invitesQuery->count();
        $usedInvites = $invitesQuery->where('used', true)->count();
        $unusedInvites = $invitesQuery->where('used', false)->count();
        $todayInvites = $invitesQuery->whereDate('created_at', now()->toDateString())->count();

        // Chart data (optional)
        $chartLabels = $recentInvites->pluck('created_at')->map(fn($d) => $d->format('Y-m-d'))->toArray();
        $chartData = $recentInvites->countBy(fn($invite) => $invite->created_at->format('Y-m-d'))->values()->toArray();

        // Generate PDF
        $pdf = Pdf::loadView('admin.reports.invites_pdf', compact(
            'recentInvites', 'totalInvites', 'usedInvites', 'unusedInvites', 'todayInvites', 'status', 'from', 'to', 'chartLabels', 'chartData'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('invite_report.pdf');
    }
}
