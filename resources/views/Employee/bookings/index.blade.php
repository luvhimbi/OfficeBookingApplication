@extends('Layout.app')

@section('title', 'My Bookings')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-0 text-primary">
                            <i class="bi bi-calendar-check me-2"></i>My Bookings
                        </h3>
                        <p class="text-muted small mb-0">View, manage, and cancel your active bookings</p>
                    </div>
{{--                    <a href="{{ route('bookings.create') }}" class="btn btn-primary rounded-pill shadow-sm">--}}
{{--                        <i class="bi bi-plus-circle me-1"></i>Book a Space--}}
{{--                    </a>--}}
                </div>

                {{-- Alerts --}}
                @foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $type)
                    @if(session($key))
                        <div class="alert alert-{{ $type }} alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-info-circle me-2"></i>{{ session($key) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                @endforeach

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table align-middle table-hover text-center border">
                        <thead class="table-light text-muted">
                        <tr>
                            <th>#</th>
                            <th>Space Type</th>
                            <th>Space Name/Number</th>
                            <th>Campus</th>
                            <th>Building</th>
                            <th>Floor</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($bookings as $booking)
                            <tr class="booking-row {{ $booking->status === 'cancelled' ? 'table-light' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-capitalize">{{ $booking->space_type }}</td>
                                <td>
                                    @if($booking->space_type === 'desk')
                                        {{ $booking->desk->desk_number ?? 'N/A' }}
                                    @elseif($booking->space_type === 'boardroom')
                                        {{ $booking->boardroom->name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>{{ $booking->campus->name ?? 'N/A' }}</td>
                                <td>{{ $booking->building->name ?? 'N/A' }}</td>
                                <td>{{ $booking->floor->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($booking->status === 'booked')
                                        <span class="badge bg-success px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                            <i class="bi bi-x-circle me-1"></i>Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('bookings.show', $booking->id) }}"
                                       class="btn btn-outline-primary btn-sm rounded-pill me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($booking->status === 'booked')
                                        <button class="btn btn-outline-danger btn-sm rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#cancelModal{{ $booking->id }}">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            {{-- Cancel Confirmation Modal --}}
                            <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $booking->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="cancelModalLabel{{ $booking->id }}">
                                                <i class="bi bi-exclamation-triangle me-2"></i>Cancel Booking
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-0">
                                                Are you sure you want to cancel this booking for
                                                <strong>{{ ucfirst($booking->space_type) }}</strong> on
                                                <strong>{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">No, Keep It</button>
                                            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger rounded-pill">
                                                    <i class="bi bi-x-circle me-1"></i>Yes, Cancel
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="10" class="text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    No bookings found. <br>
                                    <a href="{{ route('bookings.create') }}" class="btn btn-outline-primary btn-sm mt-2">
                                        <i class="bi bi-plus-circle"></i> Book Now
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        table th, table td {
            vertical-align: middle;
        }

        .table thead th {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .table td {
            font-size: 0.9rem;
        }

        .badge {
            font-size: 0.85rem;
        }

        .booking-row:hover {
            background-color: #f9fbff;
            transition: 0.2s ease-in-out;
        }

        .btn-outline-primary, .btn-outline-danger {
            border-width: 1.5px;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: white;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>
@endsection
