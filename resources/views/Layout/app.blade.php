<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - WorkSpace Hub</title>

    <!-- Bootswatch Zephyr -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/cosmo/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- FullCalendar + SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>

    <style>
        /* Remove Bootstrap’s blue focus outlines */
        :root {
            --bs-primary: #000000;
            --bs-primary-rgb: 0,0,0;
            --bs-link-color: #000000;
            --bs-link-hover-color: #333333;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* NAVBAR */
        .navbar {
            background-color:var(--bs-primary) !important;      /* deep black */
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            padding: 0.75rem 1rem;
        }

        /* NAV LINKS */
        .nav-link {
            color: #e5e5e5 !important;                  /* soft grey */
            font-weight: 500;
            padding: 0.5rem 0.75rem !important;
            border-radius: 4px;
            transition: 0.2s ease-in-out;
        }

        /* HOVER, ACTIVE, CURRENT PAGE */
        .nav-link:hover,
        .nav-link.active,
        .nav-item.show .nav-link {
            color: #ffffff !important;                  /* pure white */
            background-color: #222222 !important;       /* subtle dark grey */
        }

        /* NAVBAR BRAND */
        .navbar-brand {
            color: #ffffff !important;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* BRAND HOVER */
        .navbar-brand:hover {
            color: #d9d9d9 !important;
        }

        /* DROPDOWN MENU */
        .dropdown-menu {
            background-color: #000000 !important;       /* black dropdown */
            border: 1px solid #222222 !important;
        }

        .dropdown-item {
            color: #e5e5e5 !important;
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover,
        .dropdown-item:focus,
        .dropdown-item.active {
            background-color: #222222 !important;
            color: #ffffff !important;
        }

        /* NAVBAR TOGGLER (Hamburger icon) */
        .navbar-toggler {
            border-color: #ffffff !important;
        }

        .navbar-toggler-icon {
            filter: invert(1) !important;                          /* makes icon white */
        }


        main {
            padding: 2rem;
            min-height: calc(100vh - 120px);
        }

        .footer {
            background-color: #000000 !important;
            color: #adb5bd !important;
            padding: 20px 0;
            font-size: 14px;
        }

        .footer a {
            color: #adb5bd;
            text-decoration: none;
            margin: 0 5px;
        }

        .footer a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .footer-main {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .footer-links {
            font-size: 13px;
        }


        /* FORM FIELDS */
        .form-control,
        .form-select {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
            border-radius: 6px !important;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff !important;
            color: #000000 !important;
            border-color: #000000 !important;
            box-shadow: none !important;
        }
        /* Disabled / Readonly fields */
        .form-control:disabled,
        .form-control[readonly],
        select.form-control:disabled,
        select.form-control[readonly],
        textarea.form-control:disabled,
        textarea.form-control[readonly] {
            background-color: #e9ecef;
            color: #495057;
            opacity: 1;
            cursor: not-allowed;
            border: 1px solid #ced4da;
        }

        /* Optional: placeholder text for disabled fields */
        .form-control:disabled::placeholder,
        .form-control[readonly]::placeholder,
        select.form-control:disabled::placeholder,
        select.form-control[readonly]::placeholder,
        textarea.form-control:disabled::placeholder,
        textarea.form-control[readonly]::placeholder {
            color: #6c757d;
            opacity: 1;
        }
        /* LABELS */
        .form-label {
            color: #000000 !important;
            font-weight: 500;
        }

        /* INPUT PLACEHOLDERS */
        ::placeholder {
            color: #555555 !important;
        }

        /* TABLES */
        .table {
            --bs-table-bg: #ffffff;
            --bs-table-striped-bg: #f5f5f5;
            --bs-table-hover-bg: #eaeaea;
            --bs-table-color: #000000;
            --bs-table-border-color: #000000;
            border: 1px solid #000000;
        }

        .table th,
        .table td {
            color: #000000 !important;
            border-color: #000000 !important;
        }

        /* TABLE HEADER */
        .table thead th {
            background-color: #000000 !important;
            color: #ffffff !important;
            border-color: #000000 !important;
        }

        /* BUTTONS */
        .btn {
            border-radius: 6px !important;
            border-width: 1px !important;
            text-transform: capitalize;
        }

        /* Primary = black */
        .btn-primary {
            background-color: #000000 !important;
            border-color: #000000 !important;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background-color: #333333 !important;
            border-color: #333333 !important;
        }

        /* Secondary = white with black border */
        .btn-secondary {
            background-color: #ffffff !important;
            border-color: #000000 !important;
            color: #000000 !important;
        }

        .btn-secondary:hover {
            background-color: #f0f0f0 !important;
        }

        /* Danger (if needed): red but not neon */
        .btn-danger {
            background-color: #b00000 !important;
            border-color: #b00000 !important;
        }

        /* Pagination */
        .page-link {
            color: #000000 !important;
        }

        .page-link:hover {
            background-color: #eeeeee !important;
            color: #000000 !important;
        }

        .page-item.active .page-link {
            background-color: #000000 !important;
            border-color: #000000 !important;
            color: #ffffff !important;
        }
        /* Badges */
        .badge {
            background-color: #212529;
            color: #fff;
        }

        /* Alerts */
        .alert {
            border-radius: 1rem;
            color: #212529;
        }

        /* Cards */
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .brand-subtext {
            display: block; /* ensures it goes below the main text */
            font-size: 0.8rem; /* smaller text */
            color: #6c757d; /* optional: gray color */
        }


    </style>
</head>
<body>

<!-- TOP NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top navbar-dark">
    <div class="container-fluid">

        <a class="navbar-brand d-flex flex-column align-items-start" href="#">
            <span class="brand-main">Flexi Space</span>
            <small class="brand-subtext">Internal Booking System</small>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <ul class="navbar-nav me-auto">

                @auth
                    @if(auth()->user()->role === 'admin')

                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                               class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        <!-- Facilities Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                                <i class="bi bi-building"></i> Manage Facilities
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('campuses.index') }}" class="dropdown-item {{ request()->is('campuses') ? 'active' : '' }}"><i class="bi bi-houses"></i> Campuses</a></li>
                                <li><a href="{{ route('buildings.index') }}" class="dropdown-item {{ request()->is('buildings') ? 'active' : '' }}"><i class="bi bi-house"></i> Buildings</a></li>
                                <li><a href="{{ route('floors.index') }}" class="dropdown-item {{ request()->is('floors') ? 'active' : '' }}"><i class="bi bi-layers"></i> Floors</a></li>
                                <li><a href="{{ route('boardrooms.index') }}" class="dropdown-item {{ request()->is('boardrooms') ? 'active' : '' }}"><i class="bi bi-door-open"></i> Boardrooms</a></li>
                                <li><a href="{{ route('desks.index') }}" class="dropdown-item {{ request()->is('desks') ? 'active' : '' }}"><i class="bi bi-person-workspace"></i> Desks</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                               class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
                                <i class="bi bi-people"></i> Users
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bookings.index') }}"
                               class="nav-link {{ request()->is('bookings') ? 'active' : '' }}">
                                <i class="bi bi-calendar-check"></i> Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.index') }}"
                               class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                                <i class="bi bi-graph-up-arrow"></i> Reports
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('notifications.index') }}" class="nav-link">
                                <i class="bi bi-bell"></i> Notifications
                                @php
                                    $unread = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unread > 0)
                                    <span class="badge bg-danger">{{ $unread }}</span>
                                @endif
                            </a>
                        </li>

                    @elseif(auth()->user()->role === 'employee')

                        <li class="nav-item">
                            <a href="{{ route('employee.dashboard') }}" class="nav-link">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bookings.create') }}" class="nav-link">
                                <i class="bi bi-plus-circle"></i> Book a Space
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bookings.index') }}" class="nav-link">
                                <i class="bi bi-calendar-check"></i> My Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('notifications.index') }}" class="nav-link">
                                <i class="bi bi-bell"></i> Notifications
                                @php
                                    $unread = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unread > 0)
                                    <span class="badge bg-danger">{{ $unread }}</span>
                                @endif
                            </a>
                        </li>

                    @endif
                @endauth

            </ul>

            <ul class="navbar-nav">

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a href="{{ route('profile.show') }}" class="dropdown-item">
                                    <i class="bi bi-person-badge"></i> Profile
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form id="logoutForm" action="{{ route('logout') }}" method="POST">
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

<main>
    @yield('content')
</main>

<footer class="footer mt-auto">
    <div class="container text-center">
        <div class="footer-main mb-2">
            &copy; {{ date('Y') }} Flexi Space. All rights reserved.
        </div>
        <div class="footer-links">
            <a href="{{ route('disclaimer') }}">Disclaimer</a> |
            <a href="{{ route('privacy') }}">Privacy Policy</a> |
            <a href="{{ route('terms') }}">Terms of Service</a>
        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 2500,
        showConfirmButton: false
    });
    @endif

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
        }).then((res) => {
            if (res.isConfirmed) document.getElementById('logoutForm').submit();
        });
    }
</script>

@stack('scripts')
</body>
</html>
