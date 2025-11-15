@extends('Layout.app')

@section('title', 'Set New Password')

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Set New Password</h4>
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button class="btn btn-success">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
