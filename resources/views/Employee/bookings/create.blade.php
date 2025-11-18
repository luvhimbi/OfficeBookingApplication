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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded');

            // Function to get URL parameters
            function getUrlParameter(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
            }

            // Get parameters from URL
            const campusId = getUrlParameter('campus_id');
            const buildingId = getUrlParameter('building_id');
            const floorId = getUrlParameter('floor_id');
            const spaceType = getUrlParameter('space_type');

            console.log('URL Parameters:', { campusId, buildingId, floorId, spaceType });

            // Function to wait for element to be available
            function waitForElement(selector, callback, maxAttempts = 10, interval = 500) {
                let attempts = 0;
                const checkElement = () => {
                    attempts++;
                    const element = document.querySelector(selector);
                    if (element) {
                        console.log(`Element found: ${selector}`);
                        callback(element);
                    } else if (attempts < maxAttempts) {
                        console.log(`Waiting for element: ${selector} (attempt ${attempts})`);
                        setTimeout(checkElement, interval);
                    } else {
                        console.error(`Element not found: ${selector} after ${maxAttempts} attempts`);
                    }
                };
                checkElement();
            }

            // Function to trigger change event with retry
            function triggerChange(element, callback, retries = 3, delay = 500) {
                if (!element) {
                    console.error('Element is null');
                    return;
                }

                const event = new Event('change', { bubbles: true });
                element.dispatchEvent(event);
                console.log(`Change event triggered for:`, element);

                if (callback && retries > 0) {
                    setTimeout(() => {
                        if (!callback()) {
                            console.log(`Retrying change event for:`, element);
                            triggerChange(element, callback, retries - 1, delay);
                        }
                    }, delay);
                }
            }

            // If we have a campus ID, start the auto-population process
            if (campusId) {
                console.log('Starting auto-population with campus ID:', campusId);

                // Wait for campus select to be available
                waitForElement('#campus_id', (campusSelect) => {
                    console.log('Setting campus:', campusId);
                    campusSelect.value = campusId;

                    // Trigger change event for campus
                    triggerChange(campusSelect, () => {
                        // After campus loads, set building if available
                        if (buildingId) {
                            setTimeout(() => {
                                waitForElement('#building_id', (buildingSelect) => {
                                    console.log('Setting building:', buildingId);
                                    buildingSelect.value = buildingId;

                                    // Trigger change event for building
                                    triggerChange(buildingSelect, () => {
                                        // After building loads, set floor if available
                                        if (floorId) {
                                            setTimeout(() => {
                                                waitForElement('#floor_id', (floorSelect) => {
                                                    console.log('Setting floor:', floorId);
                                                    floorSelect.value = floorId;

                                                    // Trigger change event for floor
                                                    triggerChange(floorSelect, () => {
                                                        // Finally, set space type if available
                                                        if (spaceType) {
                                                            setTimeout(() => {
                                                                waitForElement('#space_type', (typeSelect) => {
                                                                    console.log('Setting space type:', spaceType);
                                                                    typeSelect.value = spaceType;
                                                                    const typeEvent = new Event('change', { bubbles: true });
                                                                    typeSelect.dispatchEvent(typeEvent);
                                                                });
                                                            }, 1000);
                                                        }
                                                    });
                                                });
                                            }, 1500);
                                        }
                                    });
                                });
                            }, 1500);
                        }
                    });
                });
            }
        });
    </script>
@endpush
