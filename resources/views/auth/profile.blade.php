@extends('Layout.app')

@section('title', 'My Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm border-0 rounded-4">
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
                            <input type="text"
                                   class="form-control bg-light"
                                   value="{{ $user->position }}"
                                   disabled
                                   readonly>
                            <!-- Hidden input to keep value submitted if needed -->
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
    </div>
@endsection
