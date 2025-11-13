@extends('Layout.app')

@section('title', 'Bookings')

@section('content')
    <div class="card  ">
        <div class="card-body">


            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Space</th>
                        <th>Campus / Building / Floor</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->firstname }} {{ $booking->user->lastname }}</td>
                            <td>
                                @if($booking->space_type === 'desk')
                                    Desk #{{ optional($booking->space)->desk_number }}
                                @elseif($booking->space_type === 'boardroom')
                                    {{ optional($booking->space)->name }}
                                @endif
                            </td>
                            <td>
                                {{ optional($booking->campus)->name }} /
                                {{ optional($booking->building)->name }} /
                                {{ optional($booking->floor)->name ?? '-' }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}</td>
                            <td>
                                @php
                                    $statusClass = match($booking->status) {
                                        'booked' => 'bg-primary',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} text-white">{{ ucfirst($booking->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No bookings found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
