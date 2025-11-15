<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function userReports(Request $request)
    {
        $filters = $request->only(['role','from','to']);
        $data = $this->reportService->userReports($filters);

        return view('admin.reports.users', $data);
    }

    public function inviteReports(Request $request)
    {
        $filters = $request->only(['status','from','to']);
        $data = $this->reportService->inviteReports($filters);

        return view('admin.reports.invites', $data);
    }

    public function bookings()
    {
        $data = $this->reportService->bookingReports();
        return view('admin.reports.bookings', $data);
    }
}
