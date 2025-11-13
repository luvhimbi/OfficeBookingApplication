@extends('Layout.app')

@section('title', 'Confirm Booking')

@section('content')
    <div class="container">
        <div class="card border-0 p-4">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-check-circle-fill text-success fs-3 me-2"></i>
                <h3 class="mb-0 fw-bold">Confirm Your Booking</h3>
            </div>

            <div class="booking-summary bg-light p-4 rounded-3 mb-4">
                <h5 class="fw-semibold text-primary mb-3">Booking Summary</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Location:</strong></p>
                        <ul class="list-unstyled ms-3">
                            <li><strong>Campus:</strong> {{ $bookingData['campus_name'] }}</li>
                            <li><strong>Building:</strong> {{ $bookingData['building_name'] }}</li>
                            <li><strong>Floor:</strong> {{ $bookingData['floor_name'] }}</li>
                            <li><strong>Space Type:</strong> {{ ucfirst($bookingData['space_type']) }}</li>
                            <li><strong>Space:</strong> {{ $bookingData['space_name'] }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Time Details:</strong></p>
                        <ul class="list-unstyled ms-3">
                            <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($bookingData['start_time'])->format('M d, Y') }}</li>
                            <li><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($bookingData['start_time'])->format('g:i A') }}</li>
                            <li><strong>End Time:</strong> {{ \Carbon\Carbon::parse($bookingData['end_time'])->format('g:i A') }}</li>
                            <li><strong>Duration:</strong> {{ $bookingData['duration'] }} hours</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('bookings.create') }}?campus_id={{ $bookingData['campus_id'] }}&building_id={{ $bookingData['building_id'] }}&floor_id={{ $bookingData['floor_id'] }}&space_type={{ $bookingData['space_type'] }}&space_id={{ $bookingData['space_id'] }}&start_time={{ urlencode($bookingData['start_time']) }}&end_time={{ urlencode($bookingData['end_time']) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Back to Edit
                </a>
                <form action="{{ route('bookings.confirm') }}" method="POST" id="confirmBookingForm">
                    @csrf
                    <input type="hidden" name="campus_id" value="{{ $bookingData['campus_id'] }}">
                    <input type="hidden" name="building_id" value="{{ $bookingData['building_id'] }}">
                    <input type="hidden" name="floor_id" value="{{ $bookingData['floor_id'] }}">
                    <input type="hidden" name="space_type" value="{{ $bookingData['space_type'] }}">
                    <input type="hidden" name="space_id" value="{{ $bookingData['space_id'] }}">
                    <input type="hidden" name="start_time" value="{{ $bookingData['start_time'] }}">
                    <input type="hidden" name="end_time" value="{{ $bookingData['end_time'] }}">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i> Confirm Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .booking-summary {
            border-left: 4px solid #0d6efd;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle form submission
            const form = document.getElementById('confirmBookingForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...';
                    
                    // Submit form
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams(new FormData(form))
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            Swal.fire({
                                title: 'Success!',
                                text: data.message || 'Your booking has been confirmed!',
                                icon: 'success',
                                confirmButtonText: 'View My Bookings'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '{{ route("bookings.index") }}';
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Failed to confirm booking');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'An error occurred while processing your booking.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
                });
            }
        });
    </script>
@endpush
