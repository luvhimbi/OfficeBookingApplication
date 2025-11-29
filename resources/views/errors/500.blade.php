@extends('Layout.app')

@section('title', 'Server Error')

@section('content')
    <div class="container text-center py-5">
        <h1 class="display-1 text-danger">500</h1>
        <h3 class="mb-3">Server Error</h3>
        <p class="mb-4">
            Something went wrong on our server. Please try again later or contact support if the problem persists.
        </p>
        <a href="{{ url()->previous() }}" class="btn btn-primary me-2">
            <i class="bi bi-arrow-left-circle me-1"></i> Go Back
        </a>
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <i class="bi bi-house-door me-1"></i> Go to Home
        </a>
    </div>
@endsection
