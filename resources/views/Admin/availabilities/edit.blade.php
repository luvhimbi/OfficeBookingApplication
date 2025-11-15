@extends('Layout.app')

@section('content')
    <div class="container">
        <h3>Edit Availability</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('availabilities.update', $availability) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Resource</label>
                <div class="d-flex gap-2">
                    <select name="available_type" id="available_type" class="form-select" required>
                        <option value="{{ \App\Models\Boardroom::class }}" {{ old('available_type', $availability->available_type) == \App\Models\Boardroom::class ? 'selected' : '' }}>Boardroom</option>
                        <option value="{{ \App\Models\Desk::class }}" {{ old('available_type', $availability->available_type) == \App\Models\Desk::class ? 'selected' : '' }}>Desk</option>
                    </select>

                    <select name="available_id" id="available_id" class="form-select" required>
                        <optgroup label="Boardrooms">
                            @foreach($boards as $b)
                                <option value="{{ $b->id }}" data-type="{{ \App\Models\Boardroom::class }}" {{ old('available_id', $availability->available_id) == $b->id && old('available_type', $availability->available_type) == \App\Models\Boardroom::class ? 'selected' : '' }}>
                                    {{ $b->name }} (ID: {{ $b->id }})
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="Desks">
                            @foreach($desks as $d)
                                <option value="{{ $d->id }}" data-type="{{ \App\Models\Desk::class }}" {{ old('available_id', $availability->available_id) == $d->id && old('available_type', $availability->available_type) == \App\Models\Desk::class ? 'selected' : '' }}>
                                    {{ $d->name }} (ID: {{ $d->id }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $availability->date->format('Y-m-d')) }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time', \Illuminate\Support\Str::substr($availability->start_time, 0, 5)) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time', \Illuminate\Support\Str::substr($availability->end_time, 0, 5)) }}" required>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $availability->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('availabilities.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        (function () {
            const typeSelect = document.getElementById('available_type');
            const idSelect = document.getElementById('available_id');

            function filterOptions() {
                const selectedType = typeSelect.value;
                for (let opt of idSelect.options) {
                    const dataType = opt.dataset.type;
                    if (!dataType) {
                        opt.hidden = false;
                    } else {
                        opt.hidden = dataType !== selectedType;
                    }
                }

                // ensure a visible option is selected
                if (idSelect.selectedOptions.length === 0 || idSelect.selectedOptions[0].hidden) {
                    for (let opt of idSelect.options) {
                        if (!opt.hidden) {
                            idSelect.value = opt.value;
                            break;
                        }
                    }
                }
            }

            typeSelect.addEventListener('change', filterOptions);
            window.addEventListener('DOMContentLoaded', filterOptions);
        })();
    </script>
@endsection
