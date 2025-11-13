@extends('Layout.app')

@section('content')
    <div class="container ">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="bi bi-people"></i> All Registered Users</h4>
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex" style="max-width: 300px;">
                    <input
                        type="text"
                        name="search"
                        class="form-control form-control-sm me-2 rounded-pill"
                        placeholder="Search by name, role or email..."
                        value="{{ request('search') }}"
                    >
                    <button class="btn btn-light btn-sm rounded-pill px-3" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <div class="card-body bg-light">
                @if($users->isEmpty())
                    <div class="alert alert-warning text-center rounded-pill shadow-sm">
                        <i class="bi bi-exclamation-triangle"></i> No users found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle bg-white rounded-3 shadow-sm">
                            <thead class="table-primary text-dark">
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Position</th>
                                <th>Role</th>
                                <th>Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $index => $user)
                                <tr class="table-row-hover">
                                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                    <td>{{ $user->firstname }}</td>
                                    <td>{{ $user->lastname }}</td>
                                    <td>{{ $user->position }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">Admin</span>
                                        @elseif($user->role === 'manager')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Manager</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($user->role) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .table-row-hover:hover {
            background-color: #f8f9fa !important;
            transition: background-color 0.3s ease;
        }

        .card {
            border-radius: 1rem;
        }

        input.form-control-sm {
            border-radius: 2rem;
        }

        .btn-light:hover {
            background-color: #f1f1f1;
        }
    </style>
@endsection
