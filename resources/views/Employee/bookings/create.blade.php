@extends('Layout.app')

@section('title', 'Book a Space')

@section('content')
    <div class="container">
        <div class="card border-0 p-4">
            <div class="d-flex align-items-center mb-4 justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-door-open text-primary fs-3 me-2"></i>
                    <h3 class="mb-0 fw-bold">Book a Space</h3>
                </div>

            </div>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm" class="needs-validation" novalidate>
                @csrf

                {{-- Step 1: Campus --}}
                <div class="mb-4">
                    <h5 class="fw-semibold text-primary mb-2">
                        <i class="bi bi-geo-alt-fill me-2"></i> Step 1: Choose Campus
                    </h5>
                    <select class="form-select" id="campus_id" name="campus_id" required>
                        <option value="">Select Campus</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select a campus.</div>
                </div>

                {{-- Step 2: Building --}}
                <div class="mb-4">
                    <h5 class="fw-semibold text-primary mb-2">
                        <i class="bi bi-building me-2"></i> Step 2: Select Building
                        <span class="spinner-border spinner-border-sm ms-2 d-none" id="buildingLoader"></span>
                    </h5>
                    <select class="form-select" id="building_id" name="building_id" required disabled>
                        <option value="">Select Building</option>
                    </select>
                    <div class="invalid-feedback">Please select a building.</div>
                </div>

                {{-- Step 3: Floor --}}
                <div class="mb-4">
                    <h5 class="fw-semibold text-primary mb-2">
                        <i class="bi bi-layers me-2"></i> Step 3: Select Floor
                        <span class="spinner-border spinner-border-sm ms-2 d-none" id="floorLoader"></span>
                    </h5>
                    <select class="form-select" id="floor_id" name="floor_id" required disabled>
                        <option value="">Select Floor</option>
                    </select>
                    <div class="invalid-feedback">Please select a floor.</div>
                </div>

                {{-- Step 4: Space Type --}}
                <div class="mb-4">
                    <h5 class="fw-semibold text-primary mb-2">
                        <i class="bi bi-diagram-3 me-2"></i> Step 4: Select Space Type
                    </h5>
                    <select class="form-select" id="space_type" name="space_type" required disabled>
                        <option value="">Select Type</option>
                        <option value="desk" {{ old('space_type') == 'desk' ? 'selected' : '' }}>Desk</option>
                        <option value="boardroom" {{ old('space_type') == 'boardroom' ? 'selected' : '' }}>Boardroom</option>
                    </select>
                    <div class="invalid-feedback">Please select a space type.</div>
                </div>

                {{-- Step 5: Space --}}
                <div class="mb-4">
                    <h5 class="fw-semibold text-primary mb-2">
                        <i class="bi bi-door-closed me-2"></i> Step 5: Select Space
                        <span class="spinner-border spinner-border-sm ms-2 d-none" id="spaceLoader"></span>
                    </h5>
                    <select class="form-select" id="space_id" name="space_id" required disabled>
                        <option value="">Select Space</option>
                    </select>
                    <div class="invalid-feedback">Please select a space.</div>
                </div>

                {{-- Step 6: Date & Time --}}
                <div class="mb-4">
                    <h5 class="fw-semibold text-primary mb-3">
                        <i class="bi bi-clock-history me-2"></i> Step 6: Select Date & Time
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Date</label>
                            <input type="date"
                                   class="form-control"
                                   id="date"
                                   name="date"
                                   value="{{ old('date') }}"
                                   required>
                            <div class="invalid-feedback">Please select a date.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time"
                                   class="form-control"
                                   id="start_time"
                                   name="start_time"
                                   value="{{ old('start_time') }}"
                                   required>
                            <div class="invalid-feedback">Please select a start time.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time"
                                   class="form-control"
                                   id="end_time"
                                   name="end_time"
                                   value="{{ old('end_time') }}"
                                   required>
                            <div class="invalid-feedback">Please select an end time.</div>
                        </div>
                    </div>
                </div>
                <div class="mt-3" id="slotSuggestionWrapper" style="display:none;">
                    <h6 class="fw-bold">Available Time Slots</h6>
                    <div id="slotSuggestions" class="d-flex flex-wrap gap-2"></div>
                </div>

                {{-- Submit Button --}}
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold rounded-3">
                        <i class="bi bi-calendar-check me-2"></i> Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('js/booking-form.js') }}"></script>
    <script src="{{ asset('js/autoPopulateBooking.js') }}"></script>
    <script src="{{ asset('js/DisplayingPopUps.js') }}"></script>





@endpush
