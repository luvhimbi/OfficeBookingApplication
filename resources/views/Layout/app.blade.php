<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - WorkSpace Hub</title>

    {{-- Bootswatch Zephyr Theme --}}
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/zephyr/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #212529 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 0.75rem 1rem;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-link {
            color: #adb5bd !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.2s ease-in-out;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
            background-color: rgba(0, 71, 171, 0.8);
            border-radius: 4px;
        }

        .dropdown-menu {
            background-color: #343a40;
            border: none;
        }

        .dropdown-item {
            color: #adb5bd;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 71, 171, 0.8);
            color: #fff;
        }

        main {
            padding: 2rem;
            min-height: calc(100vh - 120px);
        }

        footer {
            background-color: #212529;
            color: #adb5bd;
            padding: 20px 0;
            font-size: 14px;
        }

        @media (max-width: 992px) {
            .navbar-nav .dropdown-menu {
                background-color: #2c3034;
            }

            main {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg sticky-top bg-primary navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            WorkSpace Hub
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->is('admin/dashboard')) active @endif">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-building"></i> Manage Facilities
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item @if(request()->is('campuses')) active @endif" href="{{ route('campuses.index') }}"><i class="bi bi-houses"></i> Campuses</a></li>
                                <li><a class="dropdown-item @if(request()->is('buildings')) active @endif" href="{{ route('buildings.index') }}"><i class="bi bi-house"></i> Buildings</a></li>
                                <li><a class="dropdown-item @if(request()->is('floors')) active @endif" href="{{ route('floors.index') }}"><i class="bi bi-layers"></i> Floors</a></li>
                                <li><a class="dropdown-item @if(request()->is('boardrooms')) active @endif" href="{{ route('boardrooms.index') }}"><i class="bi bi-door-open"></i> Boardrooms</a></li>
                                <li><a class="dropdown-item @if(request()->is('desks')) active @endif" href="{{ route('desks.index') }}"><i class="bi bi-person-workspace"></i> Desks</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link @if(request()->is('admin/users')) active @endif"><i class="bi bi-people"></i> Users</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bookings.index') }}" class="nav-link @if(request()->is('bookings')) active @endif"><i class="bi bi-calendar-check"></i> Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('notifications.index') }}" class="nav-link">
                                <i class="bi bi-bell"></i> Notifications
                                @php
                                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger">{{ $unreadCount }}</span>
                                @endif
                            </a>
                        </li>
                    @elseif(auth()->user()->role === 'employee')
                        <li class="nav-item">
                            <a href="{{ route('employee.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bookings.create') }}" class="nav-link"><i class="bi bi-plus-circle"></i> Book a Space</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bookings.index') }}" class="nav-link"><i class="bi bi-calendar-check"></i> My Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('notifications.index') }}" class="nav-link">
                                <i class="bi bi-bell"></i> Notifications
                                @php
                                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger">{{ $unreadCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endauth

            </ul>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="bi bi-person-badge"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <!-- Logout Confirmation -->
                                <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="button" class="dropdown-item" onclick="confirmLogout()">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main>
    @yield('content')
</main>

<!-- Footer -->
<footer>
    <div class="container text-center">
        <p>&copy; {{ date('Y') }} WorkSpace Hub. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // SweetAlert Success Message
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

    // ✅ SweetAlert Logout Confirmation
    function confirmLogout() {
        Swal.fire({
            title: 'Logout Confirmation',
            text: 'Are you sure you want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    }
</script>

@stack('scripts')
</body>
</html>
