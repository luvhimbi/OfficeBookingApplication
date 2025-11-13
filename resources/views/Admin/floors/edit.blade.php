@extends('Layout.app')

@section('title', 'Edit Floor')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-layers-fill text-primary"></i> Edit Floor</h3>
                <a href="{{ route('floors.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Floors
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('floors.update', $floor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="building_id" class="form-label">Building</label>
                    <select name="building_id" id="building_id" class="form-select @error('building_id') is-invalid @enderror" required>
                        <option value="">Select Building</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ (old('building_id', $floor->building_id) == $building->id) ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('building_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Floor Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $floor->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Floor
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
