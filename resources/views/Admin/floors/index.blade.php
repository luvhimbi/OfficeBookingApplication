@extends('Layout.app')

@section('title', isset($building) ? 'Floors for ' . $building->name : 'Manage Floors')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>
                    <i class="bi bi-layers-fill text-primary"></i>
                    @if(isset($building))
                        Floors for {{ $building->name }}
                    @else
                        Manage Floors
                    @endif
                </h3>
                <div>
                    @if(isset($building))
                        <a href="{{ route('buildings.show', $building) }}" class="btn btn-secondary me-2">
                            <i class="bi bi-building"></i> Back to Building
                        </a>
                        <a href="{{ route('floors.create', ['building_id' => $building->id]) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Floor to {{ $building->name }}
                        </a>
                    @else
                        <a href="{{ route('floors.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add New Floor
                        </a>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Building</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($floors as $floor)
                            <tr>
                                <td>{{ $floor->id }}</td>
                                <td>{{ $floor->name }}</td>
                                <td>{{ $floor->building->name }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('floors.show', $floor) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('floors.edit', $floor) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('floors.destroy', $floor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this floor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No floors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
