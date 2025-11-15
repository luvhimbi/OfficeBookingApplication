@extends('Layout.app')

@section('title','Invite Reports')

@section('content')
    <div class="container mt-4">

        <!-- Invite Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Total Invites</h6>
                        <h4>{{ $totalInvites }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Used Invites</h6>
                        <h4>{{ $usedInvites }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Unused Invites</h6>
                        <h4>{{ $unusedInvites }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Sent Today</h6>
                        <h4>{{ $todayInvites }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5>Filter Invites</h5>
                <form method="GET" action="{{ route('admin.reports.invites') }}" class="row g-3">
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option value="used" @selected(request('status')=='used')>Used</option>
                            <option value="unused" @selected(request('status')=='unused')>Unused</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>From Date</label>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="col-md-3">
                        <label>To Date</label>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Invites Table -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5>Recent Invites</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Sent On</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentInvites as $invite)
                            <tr>
                                <td>{{ $invite->email }}</td>
                                <td>{{ $invite->firstname }} {{ $invite->lastname }}</td>
                                <td>{{ ucfirst($invite->role) }}</td>
                                <td>
                                    @if($invite->used)
                                        <span class="badge bg-success">Used</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Unused</span>
                                    @endif
                                </td>
                                <td>{{ $invite->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $recentInvites->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Invites Sent in Last 7 Days</h5>
                <canvas id="inviteChart" height="100"></canvas>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('inviteChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Invites Sent',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: { beginAtZero: true, precision: 0 }
                }
            }
        });
    </script>
@endpush
