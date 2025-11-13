@extends('Layout.app')

@section('title', 'Floor Details')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-layers-fill text-primary"></i> Floor Details</h3>
                <div>
                    <a href="{{ route('floors.edit', $floor) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('floors.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Floors
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th style="width: 30%">ID:</th>
                            <td>{{ $floor->id }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $floor->name }}</td>
                        </tr>
                        <tr>
                            <th>Building:</th>
                            <td>{{ $floor->building->name }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $floor->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $floor->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <form action="{{ route('floors.destroy', $floor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this floor?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete Floor
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
