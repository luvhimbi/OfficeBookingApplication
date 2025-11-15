@extends('Layout.app')

@section('title','Booking Reports')

@section('content')
    <div class="container mt-4">

        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h4>Booking Statistics</h4>

                <ul class="list-group">
                    <li class="list-group-item">Total Bookings: <strong>{{ $totalBookings }}</strong></li>
                    <li class="list-group-item">Upcoming: <strong>{{ $upcoming }}</strong></li>
                    <li class="list-group-item">Cancelled: <strong>{{ $cancelled }}</strong></li>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow">
            <div class="card-body">
                <h4>Recent Bookings</h4>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User</th><th>Date</th><th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($recentBookings as $b)
                        <tr>
                            <td>{{ $b->user->firstname ?? 'N/A' }}</td>
                            <td>{{ $b->booking_date }}</td>
                            <td>{{ $b->status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
