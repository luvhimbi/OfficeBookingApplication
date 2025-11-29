@php use Illuminate\Support\Facades\Auth; @endphp
@extends('Layout.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="container text-center py-5">
        <h1 class="display-1 text-warning">404</h1>
        <h3 class="mb-3">Oops! Page Not Found</h3>
        <p class="mb-4">
            The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>

        @php
            $homeUrl = '/'; // fallback for guests

            if (Auth::check()) {
                $user = Auth::user();

                if ($user->role === 'admin' && request()->routeIs('admin.dashboard') === false) {
                    $homeUrl = route('admin.dashboard');
                } elseif ($user->role === 'employee' && request()->routeIs('employee.dashboard') === false) {
                    $homeUrl = route('employee.dashboard');
                } else {
                    $homeUrl = url('/'); // fallback to avoid loop
                }
            }
        @endphp

        <a href="{{ $homeUrl }}" class="btn btn-primary">
            <i class="bi bi-house-door me-1"></i> Go to Home
        </a>
    </div>
@endsection
