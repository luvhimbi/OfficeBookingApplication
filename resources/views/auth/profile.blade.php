@extends('Layout.app')

@section('title', 'My Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7">
            {{-- Profile & Password Section --}}
            <div class="card  border-0 mb-4">
                <div class="card-body p-4">
                    <h3 class="mb-4"><i class="bi bi-person-badge text-primary"></i> My Profile</h3>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">First Name</label>
                                <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->position }}" disabled readonly>
                            <input type="hidden" name="position" value="{{ $user->position }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password (Leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2FA Section --}}
            <div class="card  border-0">
                <div class="card-body p-4">
                    <h3 class="mb-4"><i class="bi bi-shield-lock text-primary"></i> Two-Factor Authentication (2FA)</h3>

                    {{-- Constant status alert --}}
                    <div class="alert {{ $user->two_factor_enabled ? 'alert-success' : 'alert-warning' }}">
                        2FA is currently <strong>{{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}</strong>.
                    </div>

                    {{-- Success message after toggle --}}
                    @if(session('2fa_success'))
                        <div class="alert alert-success">{{ session('2fa_success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.2fa.toggle') }}">
                        @csrf
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="two_factor_enabled" id="two_factor_enabled" {{ $user->two_factor_enabled ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="two_factor_enabled">
                                {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">Toggle to enable or disable two-factor authentication using email.</small>
                    </form>
                </div>
            </div>

            {{-- Add this after the 2FA section --}}
            <div class="card border-0 mt-4">
                <div class="card-body p-4">
                    <h3 class="mb-4"><i class="bi bi-device-hdd text-primary"></i> Devices & Sessions</h3>

                    <p class="mb-3">View all devices and sessions currently logged into your account. You can log out from any device you don’t recognize.</p>

                    <a href="{{ route('profile.devices') }}" class="btn btn-info">
                        <i class="bi bi-box-arrow-in-right"></i> View Devices
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
