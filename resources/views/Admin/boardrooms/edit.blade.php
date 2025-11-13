@extends('Layout.app')

@section('title', 'Edit Boardroom')

@section('content')
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-door-open-fill text-primary me-2"></i> Edit Boardroom</h3>
                <a href="{{ route('boardrooms.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('boardrooms.update', $boardroom) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Boardroom Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Boardroom Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $boardroom->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Capacity --}}
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                           id="capacity" name="capacity" value="{{ old('capacity', $boardroom->capacity) }}" min="1" required>
                    @error('capacity')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campus --}}
                <div class="mb-3">
                    <label for="campus_id" class="form-label">Campus</label>
                    <select class="form-select @error('campus_id') is-invalid @enderror" id="campus_id" name="campus_id" required>
                        <option value="">Select Campus</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ old('campus_id', $boardroom->campus_id) == $campus->id ? 'selected' : '' }}>
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
                                {{ old('building_id', $boardroom->building_id) == $building->id ? 'selected' : '' }}>
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
                                {{ old('floor_id', $boardroom->floor_id) == $floor->id ? 'selected' : '' }}>
                                {{ $floor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('floor_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Boardroom
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS for cascading selects --}}
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
                    // Reset selection if current selection is hidden
                    if (!Array.from(selectElement.options).some(opt => opt.selected && opt.style.display != 'none')) {
                        selectElement.value = '';
                    }
                }

                campusSelect.addEventListener('change', () => {
                    filterOptions(buildingSelect, 'campus', campusSelect.value);
                    filterOptions(floorSelect, 'building', '');
                });

                buildingSelect.addEventListener('change', () => {
                    filterOptions(floorSelect, 'building', buildingSelect.value);
                });

                // Initial filter
                if (campusSelect.value) filterOptions(buildingSelect, 'campus', campusSelect.value);
                if (buildingSelect.value) filterOptions(floorSelect, 'building', buildingSelect.value);
            });
        </script>
    @endpush

@endsection
