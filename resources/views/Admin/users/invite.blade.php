@extends('Layout.app')

@section('title','Invite User')

@section('content')
    <div class="container mt-4">
        <div class="row">

            {{-- LEFT SIDE – INVITE FORM --}}
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white text-black py-3 border-bottom">
                        <h4 class="mb-0 fw-bold text-black">
                            <i class="bi bi-envelope-plus me-2 text-black"></i> Invite New User
                        </h4>
                    </div>

                    <div class="card-body p-4 bg-white">

                        @if(session('success'))
                            <div class="alert alert-success rounded-pill">
                                <i class="bi bi-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.invites.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="fw-semibold text-black">Email</label>
                                <input type="email" name="email" class="form-control rounded-pill" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-semibold text-black">Firstname</label>
                                    <input type="text" name="firstname" class="form-control rounded-pill" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-semibold text-black">Lastname</label>
                                    <input type="text" name="lastname" class="form-control rounded-pill" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-semibold text-black">Role</label>
                                <select name="role" class="form-control rounded-pill" required>
                                    <option value="employee">Employee</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <button class="btn btn-dark px-4 py-2 rounded-pill fw-bold">
                                <i class="bi bi-send-check me-1"></i> Send Invite
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE – RECENT INVITES --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white text-black py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-black">
                            <i class="bi bi-clock-history me-2 text-black"></i> Recent Invites
                        </h5>
                    </div>

                    <div class="card-body p-3 bg-white" style="max-height: 480px; overflow-y:auto;">

                        @if($invites->isEmpty())
                            <div class="alert alert-secondary text-center rounded-pill">
                                <i class="bi bi-info-circle"></i> No invites yet.
                            </div>
                        @else
                            @foreach($invites as $invite)
                                <div class="p-3 mb-3 rounded-4 shadow-sm bg-light">

                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1 fw-bold text-black">
                                            {{ $invite->firstname }} {{ $invite->lastname }}
                                        </h6>

                                        @if($invite->used)
                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                Used
                                            </span>
                                        @else
                                            <span class="badge bg-warning rounded-pill px-3 py-2">
                                                Pending
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-muted small mb-1">{{ $invite->email }}</p>

                                    <span class="badge bg-secondary">{{ ucfirst($invite->role) }}</span>

                                    <p class="small mt-2 text-muted">
                                        <i class="bi bi-calendar"></i> {{ $invite->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .form-control {
            padding: 0.75rem 1.2rem;
        }
        .card {
            border-radius: 1rem;
        }
        .shadow-lg {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }
    </style>
@endsection
