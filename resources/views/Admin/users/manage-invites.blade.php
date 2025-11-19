@extends('Layout.app')

@section('title', 'Manage Invites')

@section('content')
    <div class="container mt-4">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-white text-black py-3 d-flex justify-content-between align-items-center border-bottom">
                <h4 class="mb-0 fw-bold text-black"><i class="bi bi-envelope-paper text-black"></i> Manage Invites</h4>

                <a href="{{ route('admin.invites.create') }}" class="btn btn-outline-dark rounded-pill">
                    <i class="bi bi-plus-circle"></i> New Invite
                </a>
            </div>

            <div class="card-body bg-white">

                @if (session('success'))
                    <div class="alert alert-success rounded-pill">{{ session('success') }}</div>
                @endif

                @if ($invites->isEmpty())
                    <div class="alert alert-secondary text-center rounded-pill shadow-sm">
                        <i class="bi bi-exclamation-triangle"></i> No invites found.
                    </div>
                @else

                    <div class="table-responsive">
                        <table class="table table-hover align-middle bg-white rounded-3 shadow-sm">
                            <thead class="bg-white border-bottom text-black fw-bold">
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Sent On</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($invites as $index => $invite)
                                <tr class="table-row-hover">
                                    <td class="text-black">{{ ($invites->currentPage() - 1) * $invites->perPage() + $index + 1 }}</td>

                                    <td class="text-black">{{ $invite->email }}</td>

                                    <td class="text-black">{{ $invite->firstname }} {{ $invite->lastname }}</td>

                                    <td>
                                        @if($invite->role === 'admin')
                                            <span class="badge bg-dark px-3 py-2 rounded-pill">Admin</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($invite->role) }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($invite->used)
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Accepted</span>
                                        @else
                                            <span class="badge bg-warning px-3 py-2 rounded-pill">Pending</span>
                                        @endif
                                    </td>

                                    <td class="text-black">{{ $invite->created_at->format('d M Y, H:i') }}</td>

                                    <td class="d-flex gap-2">

                                        @if(!$invite->used && (!$invite->expires_at || now()->lt($invite->expires_at)))
                                            <a href="{{ route('admin.invites.create') }}?email={{ $invite->email }}"
                                               class="btn btn-sm btn-outline-dark rounded-pill">
                                                <i class="bi bi-arrow-repeat"></i> Resend
                                            </a>
                                        @endif

                                        <form id="delete-invite-{{ $invite->id }}"
                                              action="{{ route('admin.invites.destroy', $invite->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    onclick="confirmDelete('delete-invite-{{ $invite->id }}')"
                                                    class="btn btn-sm btn-outline-danger rounded-pill">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $invites->links('pagination::bootstrap-5') }}
                    </div>

                @endif

            </div>
        </div>
    </div>

    <style>
        .table-row-hover:hover {
            background-color: #f2f2f2 !important;
            transition: 0.3s;
        }
    </style>
@endsection
