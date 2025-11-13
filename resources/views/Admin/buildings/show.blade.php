@extends('Layout.app')

@section('title', 'Building Details')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-building-fill text-primary"></i> Building Details</h3>
                <div>
                    <a href="{{ route('buildings.edit', $building) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('buildings.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Buildings
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th style="width: 30%">ID:</th>
                            <td>{{ $building->id }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $building->name }}</td>
                        </tr>
                        <tr>
                            <th>Campus:</th>
                            <td>{{ $building->campus->name }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($building->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $building->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $building->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Floors Section -->
            <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="bi bi-layers-fill text-primary"></i> Floors</h4>
                    <a href="{{ route('floors.create', ['building_id' => $building->id]) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Add Floor
                    </a>
                </div>

                @if($floors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($floors as $floor)
                                    <tr>
                                        <td>{{ $floor->id }}</td>
                                        <td>{{ $floor->name }}</td>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        No floors have been added to this building yet.
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <form action="{{ route('buildings.destroy', $building) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this building?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete Building
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
