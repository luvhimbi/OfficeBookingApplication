@extends('Layout.app')

@section('content')
    <div class="container mt-4">
        <div class="card border-0 shadow-sm">

            <!-- Header -->
            <div class="card-header bg-white text-black py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <h4 class="fw-bold mb-2 mb-md-0 text-black">
                        <i class="bi bi-people-fill me-2 text-black"></i> Registered Users
                    </h4>

                    <div class="d-flex flex-wrap gap-2">

                        <a href="{{ route('admin.invites.index') }}"
                           class="btn btn-outline-dark fw-semibold px-4 rounded-pill shadow-sm">
                            <i class="bi bi-envelope"></i> Invites
                        </a>

                        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex">
                            <input
                                type="text"
                                name="search"
                                class="form-control form-control-sm rounded-start-pill shadow-sm"
                                placeholder="Search name, role, email..."
                                style="width: 200px;"
                                value="{{ request('search') }}"
                            >
                            <button class="btn btn-outline-dark btn-sm rounded-end-pill px-3 shadow-sm" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Body -->
            <div class="card-body bg-white rounded-bottom-4">

                @if($users->isEmpty())
                    <div class="alert alert-secondary text-center rounded-pill shadow-sm mt-3">
                        <i class="bi bi-exclamation-circle"></i> No users found.
                    </div>
                @else

                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle bg-white rounded-3 shadow-sm overflow-hidden">
                            <thead class="bg-white border-bottom text-black fw-bold">
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
                                    <td class="text-black">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                    <td class="text-black">{{ $user->firstname }}</td>
                                    <td class="text-black">{{ $user->lastname }}</td>
                                    <td class="text-black">{{ $user->position }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-dark px-3 py-2 rounded-pill">Admin</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($user->role) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-black">{{ $user->email }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>

                @endif
            </div>
        </div>
    </div>

    <!-- Custom Styling -->
    <style>
        .table-row-hover:hover {
            background-color: #f2f2f2 !important;
            transition: 0.2s ease-in-out;
            cursor: pointer;
        }

        .card-header h4 {
            letter-spacing: 0.5px;
        }

        .table {
            border-radius: 1rem;
            overflow: hidden;
        }

        .form-control-sm {
            border-radius: 2rem !important;
        }

        .btn-outline-dark {
            border-radius: 2rem;
        }
    </style>
@endsection
