@extends('Layout.app')

@section('title','User Reports')

@section('content')
    <div class="container mt-4">

        {{-- Filters --}}
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h4>Filter Users</h4>
                <form method="GET" action="{{ route('admin.reports.users') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="admin" @if(request('role')=='admin') selected @endif>Admin</option>
                            <option value="employee" @if(request('role')=='employee') selected @endif>Employee</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="from" class="form-label">From</label>
                        <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="to" class="form-label">To</label>
                        <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- User Statistics --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow text-center p-3">
                    <h6>Total Users</h6>
                    <h3 class="fw-bold">{{ $totalUsers }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow text-center p-3">
                    <h6>Admins</h6>
                    <h3 class="fw-bold">{{ $admins }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow text-center p-3">
                    <h6>Employees</h6>
                    <h3 class="fw-bold">{{ $employees }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow text-center p-3">
                    <h6>New Today</h6>
                    <h3 class="fw-bold">{{ $activeToday }}</h3>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h4>User Growth</h4>
                <canvas id="usersChart" height="100"></canvas>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h4>Recent Users</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                        <tr>
                            <th>Name</th><th>Email</th><th>Role</th><th>Date Joined</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($recentUsers as $user)
                            <tr>
                                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $recentUsers->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('usersChart').getContext('2d');
        const usersChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels), // Array of dates
                datasets: [{
                    label: 'Users Joined',
                    data: @json($chartData), // Array of counts
                    backgroundColor: 'rgba(0,123,255,0.2)',
                    borderColor: 'rgba(0,123,255,1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { beginAtZero: true, title: { display: true, text: 'Number of Users' } }
                }
            }
        });
    </script>

@endsection
