@extends('Layout.app')

@section('title', 'Booking Details')

@section('content')
    <div class="container my-5">
        <div class="card ">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold"><i class="bi bi-eye text-primary"></i> Booking Details</h3>
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Back to My Bookings
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Space Type:</strong> {{ ucfirst($booking->space_type) }}
                    </div>
                    <div class="col-md-6">
                        <strong>Space Name/Number:</strong>
                        @if($booking->space_type === 'desk')
                            {{ $booking->desk->desk_number ?? 'N/A' }}
                        @elseif($booking->space_type === 'boardroom')
                            {{ $booking->boardroom->name ?? 'N/A' }}
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Campus:</strong> {{ $booking->campus->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Building:</strong> {{ $booking->building->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Floor:</strong> {{ $booking->floor->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Start Time:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong>End Time:</strong> {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                    </div>
                    <div class="col-12">
                        <strong>Booked By:</strong> {{ $booking->user->firstname }} {{ $booking->user->lastname }} ({{ $booking->user->email }})
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-body strong {
            display: inline-block;
            width: 140px;
            color: #4e73df;
        }
    </style>
@endsection
