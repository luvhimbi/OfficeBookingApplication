@extends('Layout.app')

@section('title', 'Reset Link Invalid')

@section('content')
    <div class="container text-center py-5">
        <h1 class="text-danger">Reset Link Invalid or Expired</h1>
        <p>Your password reset link is either expired or has already been used.</p>
        <a href="{{ route('password.request') }}" class="btn btn-primary mt-3">
            Request a New Reset Link
        </a>
    </div>
@endsection
