@extends('Layout.app')

@section('title', 'Access Forbidden')
@section('code', '403')
@section('message')
    <div class="text-center py-5">
        <h1 class="display-1 text-danger">403</h1>
        <h3 class="mb-3">Access Forbidden</h3>
        <p class="mb-4">
            {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
        </p>
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="bi bi-house-door me-1"></i> Go to Home
        </a>
    </div>
@endsection

