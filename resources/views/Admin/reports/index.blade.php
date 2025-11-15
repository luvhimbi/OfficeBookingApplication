@extends('Layout.app')

@section('title','Reports')

@section('content')
    <div class="container mt-2">
        <div class="card">
            <div class="card-body">
                <h3 class="mb-4 fw-bold text-primary"><i class="bi bi-bar-chart"></i> Reports Dashboard</h3>
                <p class="text-muted mb-4">Select a report type to view detailed analytics.</p>

                <div class="row g-3">

                    <!-- User Reports -->
                    <div class="col-md-4">
                        <div class="card text-center h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
                                <h5 class="card-title fw-bold">User Reports</h5>
                                <p class="card-text text-muted">View all registered users and their activity.</p>
                                <a href="{{ route('admin.reports.users') }}" class="btn btn-primary mt-auto">View Report</a>
                            </div>
                        </div>
                    </div>

                    <!-- Invite Reports -->
                    <div class="col-md-4">
                        <div class="card text-center h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-envelope-fill display-4 text-secondary mb-3"></i>
                                <h5 class="card-title fw-bold">Invite Reports</h5>
                                <p class="card-text text-muted">Track invitations sent and their statuses.</p>
                                <a href="{{ route('admin.reports.invites') }}" class="btn btn-secondary mt-auto">View Report</a>
                            </div>
                        </div>
                    </div>

                    <!-- Bookings Reports -->
                    <div class="col-md-4">
                        <div class="card text-center h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-calendar-check-fill display-4 text-info mb-3"></i>
                                <h5 class="card-title fw-bold">Bookings Reports</h5>
                                <p class="card-text text-muted">Analyze space bookings and utilization trends.</p>
                                <a href="{{ route('admin.reports.bookings') }}" class="btn btn-info mt-auto">View Report</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection
