@extends('Layout.app')

@section('title', 'Login')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center bg-light">
        <div class="row w-100 mx-0 justify-content-center">

            <div class="col-md-6 col-lg-5 col-xl-4 bg-white  p-4">

                <div class="text-center mb-4">
                    <h3 class="fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2 text-primary"></i>Login
                    </h3>
                    <p class="text-muted small mb-0">Access your WorkSpace Hub account</p>
                </div>

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            <i class="bi bi-envelope me-1"></i> Email
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               required
                               autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">
                            <i class="bi bi-lock me-1"></i> Password
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Enter your password"
                               required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">
                            Forgot Password?
                        </a>
                    </div>

                    <div class="d-grid">
                        <button type="submit" id="submit" class="btn btn-primary fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </button>
                    </div>
                </form>



            </div>

        </div>
    </div>

    @push('styles')
        <style>

            .form-control:focus {
                border-color: #4e73df;
                box-shadow: 0 0 0 0.15rem rgba(78, 115, 223, 0.25);
            }
            .btn-primary {
                background-color: #4e73df;
                border-color: #4e73df;
            }
            .btn-primary:hover {
                background-color: #395dc5;
                border-color: #395dc5;
            }
        </style>
    @endpush
@endsection
