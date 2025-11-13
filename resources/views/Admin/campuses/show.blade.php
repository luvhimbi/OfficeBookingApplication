@extends('Layout.app')

@section('title', 'Campus Details')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2><i class="bi bi-building text-primary"></i> Campus Details</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('campuses.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('campuses.edit', $campus) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title">{{ $campus->name }}</h4>
                        <p class="text-muted">
                            @if($campus->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="text-muted">Created: {{ $campus->created_at->format('M d, Y') }}</p>
                        <p class="text-muted">Last Updated: {{ $campus->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <hr>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5>Address</h5>
                        <p>{{ $campus->address ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>City</h5>
                        <p>{{ $campus->city ?? 'Not specified' }}</p>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCampusModal">
                        <i class="bi bi-trash"></i> Delete Campus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteCampusModal" tabindex="-1" aria-labelledby="deleteCampusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCampusModalLabel">Confirm Delete</h5>
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
@endsection
