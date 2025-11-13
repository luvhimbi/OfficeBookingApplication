@extends('Layout.app')

@section('title', 'Manage Campuses')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2><i class="bi bi-building text-primary"></i> Manage Campuses</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('campuses.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Add New Campus
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                @if($campuses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($campuses as $campus)
                                    <tr>
                                        <td>{{ $campus->name }}</td>
                                        <td>{{ $campus->address ?? 'N/A' }}</td>
                                        <td>{{ $campus->city ?? 'N/A' }}</td>
                                        <td>
                                            @if($campus->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('campuses.show', $campus) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('campuses.edit', $campus) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCampusModal{{ $campus->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteCampusModal{{ $campus->id }}" tabindex="-1" aria-labelledby="deleteCampusModalLabel{{ $campus->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteCampusModalLabel{{ $campus->id }}">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete the campus <strong>{{ $campus->name }}</strong>?
                                                    This action cannot be undone.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('campuses.destroy', $campus) }}" method="POST">
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
                        <p class="lead">No campuses found</p>
                        <a href="{{ route('campuses.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add New Campus
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
