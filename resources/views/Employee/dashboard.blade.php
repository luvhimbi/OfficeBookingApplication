@extends('Layout.app')

@section('title', 'Employee Dashboard')

@section('content')
    <style>
        .nav-tabs .nav-link {
            color: #000 !important;
            font-weight: 500;
            border: none;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }
        .nav-tabs .nav-link:hover {
            border-bottom: 2px solid #000;
        }
        .nav-tabs .nav-link.active {
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
            background-color: transparent !important;
            font-weight: 600;
        }

        .badge-status {
            font-size: 0.85rem;
            padding: 0.4em 0.7em;
            border-radius: 0.5rem;
        }
        .badge-booked { background-color: #0d6efd; color: #fff; }
        .badge-cancelled { background-color: #dc3545; color: #fff; }
        .badge-completed { background-color: #198754; color: #fff; }

        .hover-shadow:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            transition: all 0.3s ease-in-out;
        }
    </style>

    <div class="container">

        {{-- Greeting --}}
        <div class="card greeting-card mb-4 border-0 shadow">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <h3 class="mb-0 me-3" id="greeting-text">Good Morning</h3>
                            <span class="day-badge" id="current-day">Tuesday</span>
                        </div>
                        <p class="mb-0">
                            Welcome back, <strong>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</strong>!
                            Welcome to your dashboard.
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-sun greeting-icon" id="greeting-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        {{-- TODAY’S BOOKINGS --}}
        <div class="card shadow border-0 mb-4">
            <div class="card-header">
                <h5 class="mb-0">Today's Bookings</h5>
            </div>
            <div class="card-body">
                @if($todaysBookings->isEmpty())
                    <p class="text-muted">You have no bookings for today.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Campus</th>
                                <th>Building</th>
                                <th>Floor</th>
                                <th>Space</th>
                                <th>Date</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($todaysBookings as $index => $booking)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $booking->campus->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->building->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->floor->name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $booking->space_type === 'desk'
                                            ? $booking->desk->desk_number ?? 'N/A'
                                            : ($booking->boardroom->name ?? 'N/A') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                                    <td>
                                        <span class="badge badge-status badge-{{ $booking->status }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Book --}}
        @if($favoriteSpaces->isNotEmpty())
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Book</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($favoriteSpaces as $space)
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title mb-2">
                                            {{ $space->campus->name ?? 'N/A' }} →
                                            {{ $space->building->name ?? 'N/A' }} →
                                            {{ $space->floor->name ?? 'N/A' }}
                                        </h6>
                                        <p class="card-text mb-1"><strong>Space Type:</strong> {{ ucfirst($space->space_type) }}</p>
                                        <p class="card-text mb-3"><strong>Times Booked:</strong> {{ $space->booked_count }}</p>
                                        <a href="{{ route('bookings.create', [
                                            'campus_id' => $space->campus_id,
                                            'building_id' => $space->building_id,
                                            'floor_id' => $space->floor_id,
                                            'space_type' => $space->space_type,
                                            'space_id' => $space->space_id,
                                        ]) }}"
                                           class="btn btn-primary mt-auto">
                                            Book Again
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif



        {{-- BOOKINGS TABS --}}
        <div class="card shadow border-0">
            <div class="card-header">
                <h5 class="mb-0">My Bookings</h5>
            </div>
            <div class="card-body">

                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-3" id="bookingTabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">
                            Upcoming Bookings
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
                            Past Bookings
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab">
                            Cancelled Bookings
                        </button>
                    </li>

                </ul>

                {{-- Tab Contents --}}
                <div class="tab-content" id="bookingTabsContent">

                    {{-- UPCOMING TAB --}}
                    <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                        @if($upcomingBookings->isEmpty())
                            <p class="text-muted">No upcoming bookings found.</p>
                        @else
                            @include('partials.booking-table', ['bookings' => $upcomingBookings])
                        @endif
                    </div>

                    {{-- PAST TAB --}}
                    <div class="tab-pane fade" id="past" role="tabpanel">
                        @if($pastBookings->isEmpty())
                            <p class="text-muted">No past bookings found.</p>
                        @else
                            @include('partials.booking-table', ['bookings' => $pastBookings])
                        @endif
                    </div>

                    {{-- CANCELLED TAB --}}
                    <div class="tab-pane fade" id="cancelled" role="tabpanel">
                        @if($cancelledBookings->isEmpty())
                            <p class="text-muted">No cancelled bookings.</p>
                        @else
                            @include('partials.booking-table', ['bookings' => $cancelledBookings])
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const now = new Date();
                const hour = now.getHours();
                const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                const day = days[now.getDay()];

                document.getElementById('current-day').textContent = day;
                const greetingText = document.getElementById('greeting-text');
                const greetingIcon = document.getElementById('greeting-icon');

                if (hour < 12) {
                    greetingText.textContent = 'Good Morning';
                    greetingIcon.className = 'bi bi-sun greeting-icon text-warning';
                } else if (hour < 18) {
                    greetingText.textContent = 'Good Afternoon';
                    greetingIcon.className = 'bi bi-brightness-high greeting-icon text-info';
                } else {
                    greetingText.textContent = 'Good Evening';
                    greetingIcon.className = 'bi bi-moon-stars greeting-icon text-dark';
                }
            });

            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
            @endif
        </script>
    @endpush
@endsection
