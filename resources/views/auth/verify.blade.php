@extends('Layout.auth')

@section('title', 'Two-Factor Authentication')

@section('content')
    <div class="container my-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h5 class="card-title mb-4">Enter the 6-digit code sent to your email</h5>
                <form method="POST" action="{{ route('2fa.verify') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">
                    <div class="mb-3">
                        <input type="text" name="two_factor_code" class="form-control" placeholder="123456" required autofocus>
                        @error('two_factor_code')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Verify</button>
                </form>
            </div>
        </div>
    </div>
@endsection
