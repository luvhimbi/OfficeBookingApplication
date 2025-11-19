@extends('Layout.app')

@section('title', 'Manage Buildings')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2><i class="bi bi-building text-primary"></i> Manage Buildings</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('buildings.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Add New Building
                </a>
            </div>
        </div>

        {{-- Search Form --}}
        <form method="GET" action="{{ route('buildings.index') }}" class="mb-3">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search buildings..." class="form-control">
        </form>

        <div class="card shadow-sm">
            <div class="card-body">
                @if($buildings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Campus</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($buildings as $building)
                                <tr>
                                    <td>{{ $building->name }}</td>
                                    <td>{{ $building->campus->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($building->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('buildings.show', $building) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('buildings.edit', $building) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBuildingModal{{ $building->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteBuildingModal{{ $building->id }}" tabindex="-1" aria-labelledby="deleteBuildingModalLabel{{ $building->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteBuildingModalLabel{{ $building->id }}">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete the building <strong>{{ $building->name }}</strong>? This action cannot be undone.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('buildings.destroy', $building) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                            </tbody>
                        </table>
                    </div>


                @else
                    <div class="text-center py-4">
                        <i class="bi bi-building text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="lead">No buildings found</p>
                        <a href="{{ route('buildings.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add New Building
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
