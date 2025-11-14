@extends('Layout.auth')

@section('title', 'Two-Factor Authentication')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center bg-light min-vh-100">
        <div class="row w-100 mx-0 justify-content-center">

            <div class="col-md-6 col-lg-5 col-xl-4">

                <div class="card shadow-sm rounded-4 border-0 p-4">

                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">
                            <i class="bi bi-shield-lock me-2"></i>Two-Factor Authentication
                        </h3>
                        <p class="text-muted small mb-0">Enter the 6-digit code sent to your email</p>
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

                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">

                        <div class="mb-3">
                            <input type="text"
                                   name="two_factor_code"
                                   class="form-control form-control-lg text-center fs-5 @error('two_factor_code') is-invalid @enderror"
                                   placeholder="123456"
                                   required
                                   autofocus>
                            @error('two_factor_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary fw-semibold">
                                <i class="bi bi-check-circle me-1"></i> Verify
                            </button>
                        </div>
                    </form>

                    <p class="text-center text-muted small">
                        Didn't receive the code?
                        <button id="resendBtn" class="btn btn-link p-0" type="button" disabled>Resend OTP (<span id="timer">10</span>s)</button>
                    </p>

                    <form id="resendForm" method="POST" action="{{ route('otp.resend') }}">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let timer = 10;
            const timerEl = document.getElementById('timer');
            const resendBtn = document.getElementById('resendBtn');
            const resendForm = document.getElementById('resendForm');

            const countdown = setInterval(() => {
                timer--;
                timerEl.textContent = timer;
                if(timer <= 0){
                    clearInterval(countdown);
                    resendBtn.disabled = false;
                    resendBtn.textContent = "Resend OTP";
                }
            }, 1000);

            resendBtn.addEventListener('click', () => {
                resendForm.submit();
            });
        </script>
    @endpush

    @push('styles')
        <style>
            body {
                background-color: #f8f9fa;
            }

            .card {
                border-radius: 1rem;
            }

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

            .btn-link {
                font-weight: 500;
                text-decoration: underline;
                cursor: pointer;
            }

            .fs-5 {
                letter-spacing: 0.3rem;
            }
        </style>
    @endpush
@endsection
