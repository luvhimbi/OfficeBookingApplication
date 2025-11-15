@extends('Layout.app')

@section('title', 'Admin Dashboard')

@section('content')
        <div class="container mt-2">
            <div class="card greeting-card mb-2 border-0 shadow">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <h3 class="mb-0 me-3" id="greeting-text">Good Morning</h3>
                                <span class="day-badge" id="current-day">Tuesday</span>
                            </div>
                            <p class="mb-0">Welcome back,
                                <strong>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</strong>!
                                Ready to manage your workspace facilities today?
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="bi bi-sun greeting-icon" id="greeting-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Campuses --}}
                <div class="col-md-4">
                    <a href="{{ route('campuses.index') }}" class="text-decoration-none">
                        <div class="card border-0 h-100 hover-shadow">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                <i class="bi bi-building fs-1 text-primary mb-3"></i>
                                <h5 class="card-title">Manage Campuses</h5>
                                <p class="text-muted small">Total: <strong>{{ $campusesCount }}</strong></p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Buildings --}}
                <div class="col-md-4">
                    <a href="{{ route('buildings.index') }}" class="text-decoration-none">
                        <div class="card border-0 h-100 hover-shadow">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                <i class="bi bi-building-fill fs-1 text-primary mb-3"></i>
                                <h5 class="card-title">Manage Buildings</h5>
                                <p class="text-muted small">Total: <strong>{{ $buildingsCount }}</strong></p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Floors --}}
                <div class="col-md-4">
                    <a href="{{ route('floors.index') }}" class="text-decoration-none">
                        <div class="card border-0 h-100 hover-shadow">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                <i class="bi bi-layers fs-1 text-primary mb-3"></i>
                                <h5 class="card-title">Manage Floors</h5>
                                <p class="text-muted small">Total: <strong>{{ $floorsCount }}</strong></p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Boardrooms --}}
                <div class="col-md-4">
                    <a href="{{ route('boardrooms.index') }}" class="text-decoration-none">
                        <div class="card border-0 h-100 hover-shadow">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                                <h5 class="card-title">Manage Boardrooms</h5>
                                <p class="text-muted small">Total: <strong>{{ $boardroomsCount }}</strong></p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Desks --}}
                <div class="col-md-4">
                    <a href="{{ route('desks.index') }}" class="text-decoration-none">
                        <div class="card border-0 h-100 hover-shadow">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                <i class="bi bi-laptop fs-1 text-primary mb-3"></i>
                                <h5 class="card-title">Manage Desks</h5>
                                <p class="text-muted small">Total: <strong>{{ $desksCount }}</strong></p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Bookings --}}
                <div class="col-md-4">
                    <a href="{{ route('bookings.index') }}" class="text-decoration-none">
                        <div class="card border-0 h-100 hover-shadow">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                <i class="bi bi-calendar-check fs-1 text-primary mb-3"></i>
                                <h5 class="card-title">Manage Bookings</h5>
                                <p class="text-muted small">Total: <strong>{{ $bookingsCount }}</strong></p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Users --}}
                <div class="col-md-4">
                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                        <div class="card border-0 h-100 hover-shadow">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                <i class="bi bi-people fs-1 text-primary mb-3"></i>
                                <h5 class="card-title">Manage Users</h5>
                                <p class="text-muted small">Total: <strong>{{ $usersCount }}</strong></p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>


    {{-- Custom hover shadow --}}
    @push('styles')
        <style>
            .hover-shadow:hover {
                transform: translateY(-5px);
                transition: all 0.2s ease-in-out;
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            }
        </style>
    @endpush
    @push('scripts' )
        <script>
            // Time-based greeting and day display
            document.addEventListener('DOMContentLoaded', function() {
                const now = new Date();
                const hour = now.getHours();
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const day = days[now.getDay()];

                // Set current day
                document.getElementById('current-day').textContent = day;

                // Set greeting based on time of day
                const greetingText = document.getElementById('greeting-text');
                const greetingIcon = document.getElementById('greeting-icon');

                if (hour < 12) {
                    greetingText.textContent = 'Good Morning';
                    greetingIcon.className = 'bi bi-sun greeting-icon';
                } else if (hour < 18) {
                    greetingText.textContent = 'Good Afternoon';
                    greetingIcon.className = 'bi bi-brightness-high greeting-icon';
                } else {
                    greetingText.textContent = 'Good Evening';
                    greetingIcon.className = 'bi bi-moon-stars greeting-icon';
                }
            });

            // SweetAlert: session success message
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
            @endif

            // SweetAlert Delete Confirmation
            function confirmDelete(formId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            }
        </script>
    @endpush
@endsection
