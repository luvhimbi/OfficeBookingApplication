@extends('Layout.app')

@section('title', 'Edit Desk')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-laptop me-2 text-primary"></i> Edit Desk</h3>
                <a href="{{ route('desks.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('desks.update', $desk) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Desk Number --}}
                <div class="mb-3">
                    <label for="desk_number" class="form-label">Desk Number</label>
                    <input type="text" class="form-control @error('desk_number') is-invalid @enderror"
                           id="desk_number" name="desk_number" value="{{ old('desk_number', $desk->desk_number) }}" required>
                    @error('desk_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campus --}}
                <div class="mb-3">
                    <label for="campus_id" class="form-label">Campus</label>
                    <select class="form-select @error('campus_id') is-invalid @enderror" id="campus_id" name="campus_id" required>
                        <option value="">Select Campus</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ old('campus_id', $desk->campus_id) == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('campus_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Building --}}
                <div class="mb-3">
                    <label for="building_id" class="form-label">Building</label>
                    <select class="form-select @error('building_id') is-invalid @enderror" id="building_id" name="building_id" required>
                        <option value="">Select Building</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" data-campus="{{ $building->campus_id }}"
                                {{ old('building_id', $desk->building_id) == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('building_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Floor --}}
                <div class="mb-3">
                    <label for="floor_id" class="form-label">Floor</label>
                    <select class="form-select @error('floor_id') is-invalid @enderror" id="floor_id" name="floor_id" required>
                        <option value="">Select Floor</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}" data-building="{{ $floor->building_id }}"
                                {{ old('floor_id', $desk->floor_id) == $floor->id ? 'selected' : '' }}>
                                {{ $floor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('floor_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Desk
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS for cascading Campus → Building → Floor --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const campusSelect = document.getElementById('campus_id');
                const buildingSelect = document.getElementById('building_id');
                const floorSelect = document.getElementById('floor_id');

                function filterOptions(selectElement, dataAttr, value) {
                    Array.from(selectElement.options).forEach(opt => {
                        if (!opt.value) return;
                        opt.style.display = opt.dataset[dataAttr] == value ? 'block' : 'none';
                    });
                    selectElement.value = '';
                }

                campusSelect.addEventListener('change', () => {
                    filterOptions(buildingSelect, 'campus', campusSelect.value);
                    filterOptions(floorSelect, 'building', '');
                });

                buildingSelect.addEventListener('change', () => {
                    filterOptions(floorSelect, 'building', buildingSelect.value);
                });

                // Initial filter on page load
                if (campusSelect.value) filterOptions(buildingSelect, 'campus', campusSelect.value);
                if (buildingSelect.value) filterOptions(floorSelect, 'building', buildingSelect.value);
            });
        </script>
    @endpush

@endsection
