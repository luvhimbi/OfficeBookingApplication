@extends('Layout.app')

@section('title','Booking Reports')

@section('content')
    <div class="container mt-4">

        {{-- Booking Statistics --}}
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h4>Booking Statistics</h4>

                <ul class="list-group">
                    <li class="list-group-item">Total Bookings: <strong>{{ $totalBookings }}</strong></li>
                    <li class="list-group-item">Upcoming: <strong>{{ $upcoming }}</strong></li>
                    <li class="list-group-item">Cancelled: <strong>{{ $cancelled }}</strong></li>
                    <li class="list-group-item">Today: <strong>{{ $todayBookings }}</strong></li>
                </ul>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Recent Bookings --}}
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h4>Recent Bookings</h4>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Date</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th>Campus</th>
                        <th>Building</th>
                        <th>Floor</th>
                        <th>Space Type</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($recentBookings as $b)
                        <tr>
                            <td>{{ $b->user->firstname ?? 'N/A' }}</td>
                            <td>{{ $b->date }}</td>
                            <td>{{ $b->start_time }}</td>
                            <td>{{ $b->end_time }}</td>
                            <td>{{ $b->status }}</td>
                            <td>{{ $b->campus->name ?? 'N/A' }}</td>
                            <td>{{ $b->building->name ?? 'N/A' }}</td>
                            <td>{{ $b->floor->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($b->space_type) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $recentBookings->withQueryString()->links() }}
            </div>
        </div>

        {{-- Charts --}}
        <div class="row">

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h4>Bookings Last 7 Days</h4>
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h4>Peak Booking Hours</h4>
                        <canvas id="peakHoursChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h4>Monthly Trends</h4>
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h4>User Ranking (Top 10)</h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Total Bookings</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($userRanking as $u)
                                <tr>
                                    <td>{{ $u->user->firstname ?? 'User Deleted' }}</td>
                                    <td>{{ $u->total }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Daily bookings
        new Chart(document.getElementById('dailyChart'), {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Bookings',
                    data: @json($chartData)
                }]
            }
        });

        // Peak hours
        new Chart(document.getElementById('peakHoursChart'), {
            type: 'line',
            data: {
                labels: @json($peakHours->pluck('hour')),
                datasets: [{
                    label: 'Bookings',
                    data: @json($peakHours->pluck('total'))
                }]
            }
        });

        // Monthly trends
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: @json($monthlyTrends->pluck('month')),
                datasets: [{
                    label: 'Bookings',
                    data: @json($monthlyTrends->pluck('total'))
                }]
            }
        });
    </script>
@endpush
