@extends('Layout.app')

@section('title', 'Reset Password')

@section('content')
    <div class="container-fluid  d-flex align-items-center justify-content-center">
        <div class="row w-100 mx-0 justify-content-center">

            <div class="col-md-6 col-lg-5 col-xl-4 bg-white  p-4">

                <div class="text-center mb-4">
                    <h3 class="fw-bold">
                        <i class="bi bi-lock fs-3 text-primary me-2"></i> Reset Password
                    </h3>
                    <p class="text-muted small mb-0">Enter your email to receive a reset link</p>
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
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            <i class="bi bi-envelope me-1"></i> Email
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="you@example.com" required autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-semibold">
                            <i class="bi bi-envelope me-1"></i> Send Reset Link
                        </button>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none small">
                            <i class="bi bi-box-arrow-in-left me-1"></i> Back to Login
                        </a>
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
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form[action="{{ route('password.email') }}"]');

                form.addEventListener('submit', function(e) {
                    // Show SweetAlert loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we send your reset link.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                });
            });
        </script>
    @endpush


@endsection
