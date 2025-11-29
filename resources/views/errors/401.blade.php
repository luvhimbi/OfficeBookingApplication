@extends('Layout.app')

@section('title', 'Unauthorized Access')

@section('content')
    <div class="container text-center py-5">
        <h1 class="display-1 text-warning">401</h1>
        <h3 class="mb-3">Unauthorized</h3>
        <p class="mb-4">You do not have permission to access this page.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="bi bi-house-door me-1"></i> Go to Home
        </a>
    </div>
@endsection

