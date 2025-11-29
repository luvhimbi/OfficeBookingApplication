@extends('Layout.app')

@section('title', 'Too Many Requests')

@section('content')
    <div class="container text-center py-5">
        <h1 class="display-1 text-warning">429</h1>
        <h3 class="mb-3">Too Many Requests</h3>
        <p class="mb-4">
            You have sent too many requests in a short period of time. Please slow down and try again in a few moments.
        </p>
        <a href="{{ url()->previous() }}" class="btn btn-primary me-2">
            <i class="bi bi-arrow-left-circle me-1"></i> Go Back
        </a>
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <i class="bi bi-house-door me-1"></i> Go to Home
        </a>
    </div>
@endsection

