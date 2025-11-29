@extends('Layout.app')

@section('title', 'My Profile')

@section('content')
    <div class="container py-4">
        <div class="row">
            {{-- Left Column: Profile & Password --}}
            <div class="col-md-6">
                <div class="card border-0 mb-4">
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
            </div>

            {{-- Right Column: 2FA + Devices --}}
            <div class="col-md-6">
                {{-- 2FA Section --}}
                <div class="card border-0 mb-4">
                    <div class="card-body p-4">
                        <h3 class="mb-4"><i class="bi bi-shield-lock text-primary"></i> Two-Factor Authentication (2FA)</h3>

                        <div class="alert {{ $user->two_factor_enabled ? 'alert-success' : 'alert-warning' }}">
                            2FA is currently <strong>{{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}</strong>.
                        </div>

                        @if(session('2fa_success'))
                            <div class="alert alert-success">{{ session('2fa_success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('profile.2fa.toggle') }}" id="twoFactorForm">
                            @csrf
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="two_factor_enabled"
                                       id="two_factor_enabled"
                                    {{ $user->two_factor_enabled ? 'checked' : '' }}>
                                <label class="form-check-label" for="two_factor_enabled">
                                    {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">Toggle to enable or disable two-factor authentication using email.</small>
                        </form>
                    </div>
                {{-- Devices & Sessions --}}
                <div class="card border-0">
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
    </div>
        @push('scripts')
        <script>
            document.getElementById('two_factor_enabled').addEventListener('change', function (e) {
                e.preventDefault();

                let form = document.getElementById('twoFactorForm');
                let isEnabling = this.checked;

                // Message changes depending on enable/disable
                let title = isEnabling ? "Enable Two-Factor Authentication?" : "Disable Two-Factor Authentication?";
                let message = isEnabling
                    ? "Enabling 2FA will sign you out and require you to verify using email before logging back in."
                    : "Disabling 2FA will remove the extra verification step.";

                Swal.fire({
                    title: title,
                    text: message,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Continue",
                    cancelButtonText: "Cancel",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        // Undo the toggle if cancelled
                        this.checked = !isEnabling;
                    }
                });
            });
        </script>
    @endpush
@endsection
