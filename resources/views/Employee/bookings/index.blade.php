@extends('Layout.app')

@section('title', 'My Bookings')

@section('content')
    <div class="container ">
        <div class="card border-0 ">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold"><i class="bi bi-calendar-check text-primary"></i> My Bookings</h3>
                    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Book a Space
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Space Type</th>
                            <th>Space Name/Number</th>
                            <th>Campus</th>
                            <th>Building</th>
                            <th>Floor</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-capitalize">{{ $booking->space_type }}</td>
                                <td>
                                    @if($booking->space_type === 'desk')
                                        {{ $booking->desk->desk_number ?? 'N/A' }}
                                    @elseif($booking->space_type==='boardroom')
                                        {{ $booking->boardroom->name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>{{ $booking->campus->name ?? 'N/A' }}</td>
                                <td>{{ $booking->building->name ?? 'N/A' }}</td>
                                <td>{{ $booking->floor->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}</td>
                                <td>
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No bookings found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Make table more readable */
        table th, table td {
            vertical-align: middle;
        }

        table th {
            font-weight: 600;
        }

        table td {
            font-size: 0.95rem;
        }

        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Highlight rows on hover */
        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.1);
        }
    </style>
@endsection
