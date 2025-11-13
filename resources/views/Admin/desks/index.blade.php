@extends('Layout.app')

@section('title', 'Manage Desks')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-semibold text-primary">
                    <i class="bi bi-laptop me-2"></i> Manage Desks
                </h3>
                <a href="{{ route('desks.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Desk
                </a>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Desks Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Desk Number</th>
                        <th>Campus</th>
                        <th>Building</th>
                        <th>Floor</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($desks as $desk)
                        <tr>
                            <td>{{ $desk->id }}</td>
                            <td class="fw-medium">{{ $desk->desk_number }}</td>
                            <td>{{ $desk->campus->name ?? 'N/A' }}</td>
                            <td>{{ $desk->building->name ?? 'N/A' }}</td>
                            <td>{{ $desk->floor->name ?? 'N/A' }}</td>
                            <td>
                                @if($desk->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('desks.edit', $desk) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('desks.destroy', $desk) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this desk?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-exclamation-circle"></i> No desks found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
