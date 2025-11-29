@extends('Layout.app')

@section('title', 'Page Expired')

@section('content')
    <div class="container text-center py-5">
        <h1 class="display-1 text-danger">419</h1>
        <h3 class="mb-3">Page Expired</h3>
        <p class="mb-4">
            The page has expired due to inactivity. Please refresh the page or go back and try again.
        </p>
        <a href="{{ url()->previous() }}" class="btn btn-primary me-2">
            <i class="bi bi-arrow-left-circle me-1"></i> Go Back
        </a>
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <i class="bi bi-house-door me-1"></i> Go to Home
        </a>
    </div>
@endsection

