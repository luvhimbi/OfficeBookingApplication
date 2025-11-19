
    document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Get form elements
    const campusSelect = document.getElementById('campus_id');
    const buildingSelect = document.getElementById('building_id');
    const floorSelect = document.getElementById('floor_id');
    const typeSelect = document.getElementById('space_type');
    const spaceSelect = document.getElementById('space_id');

    // Get loader spinners
    const buildingLoader = document.getElementById('buildingLoader');
    const floorLoader = document.getElementById('floorLoader');
    const spaceLoader = document.getElementById('spaceLoader');

    // Verify elements exist
    if (!campusSelect || !buildingSelect || !floorSelect || !typeSelect || !spaceSelect) {
    console.error('One or more form elements not found');
    return;
}

    /**
     * Helper function to show loader
     */
    function showLoader(loader) {
    if (loader) loader.classList.remove('d-none');
}

    /**
     * Helper function to hide loader
     */
    function hideLoader(loader) {
    if (loader) loader.classList.add('d-none');
}

    /**
     * Helper function to reset a dropdown
     */
    function resetDropdown(select, placeholder = 'Select Option') {
    select.innerHTML = `<option value="">${placeholder}</option>`;
    select.disabled = true;
}

    /**
     * Helper function to populate a dropdown
     */
    function populateDropdown(select, items, textKey, valueKey = 'id') {
    select.innerHTML = `<option value="">Select ${select.name}</option>`;

    items.forEach(item => {
    const option = document.createElement('option');
    option.value = item[valueKey];
    option.textContent = item[textKey];
    select.appendChild(option);
});

    select.disabled = false;
}

    /**
     * Fetch data via AJAX
     */
    async function fetchData(url) {
    try {
    const response = await fetch(url, {
    method: 'GET',
    headers: {
    'X-CSRF-TOKEN': csrfToken,
    'Accept': 'application/json',
    'Content-Type': 'application/json'
}
});

    if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`);
}

    const result = await response.json();

    if (!result.success) {
    throw new Error(result.message || 'Request failed');
}

    return result.data;
} catch (error) {
    console.error('Fetch error:', error);
    alert('Error loading data: ' + error.message);
    return null;
}
}

    // ==================== CAMPUS CHANGE ====================
    campusSelect.addEventListener('change', async function() {
    const campusId = this.value;
    console.log('Campus selected:', campusId);

    // Reset dependent dropdowns
    resetDropdown(buildingSelect, 'Select Building');
    resetDropdown(floorSelect, 'Select Floor');
    typeSelect.value = '';
    typeSelect.disabled = true;
    resetDropdown(spaceSelect, 'Select Space');

    if (!campusId) {
    console.log('No campus selected');
    return;
}

    // Show loader and fetch buildings
    showLoader(buildingLoader);
    const buildings = await fetchData(`/bookings/get-buildings/${campusId}`);
    hideLoader(buildingLoader);

    if (buildings && buildings.length > 0) {
    populateDropdown(buildingSelect, buildings, 'name');
    console.log('Buildings loaded:', buildings.length);
} else {
    alert('No buildings available for this campus');
}
});

    // ==================== BUILDING CHANGE ====================
    buildingSelect.addEventListener('change', async function() {
    const buildingId = this.value;
    console.log('Building selected:', buildingId);

    // Reset dependent dropdowns
    resetDropdown(floorSelect, 'Select Floor');
    typeSelect.value = '';
    typeSelect.disabled = true;
    resetDropdown(spaceSelect, 'Select Space');

    if (!buildingId) {
    console.log('No building selected');
    return;
}

    // Show loader and fetch floors
    showLoader(floorLoader);
    const floors = await fetchData(`/bookings/get-floors/${buildingId}`);
    hideLoader(floorLoader);

    if (floors && floors.length > 0) {
    populateDropdown(floorSelect, floors, 'name');
    console.log('Floors loaded:', floors.length);
} else {
    alert('No floors available for this building');
}
});

    // ==================== FLOOR CHANGE ====================
    floorSelect.addEventListener('change', function() {
    const floorId = this.value;
    console.log('Floor selected:', floorId);

    // Reset dependent dropdowns
    typeSelect.value = '';
    resetDropdown(spaceSelect, 'Select Space');

    if (!floorId) {
    typeSelect.disabled = true;
    return;
}

    // Enable space type selection
    typeSelect.disabled = false;
});

    // ==================== SPACE TYPE CHANGE ====================
    typeSelect.addEventListener('change', async function() {
    const spaceType = this.value;
    const floorId = floorSelect.value;

    console.log('Space type selected:', spaceType);

    // Reset space dropdown
    resetDropdown(spaceSelect, 'Select Space');

    if (!spaceType || !floorId) {
    console.log('No space type or floor selected');
    return;
}

    // Show loader and fetch spaces
    showLoader(spaceLoader);
    const result = await fetchData(`/bookings/get-spaces/${floorId}/${spaceType}`);
    hideLoader(spaceLoader);

    if (result && result.length > 0) {
    spaceSelect.innerHTML = '<option value="">Select Space</option>';

    result.forEach(space => {
    const option = document.createElement('option');
    option.value = space.id;
    if (spaceType === 'desk') {
    option.textContent = `Desk #${space.desk_number}`;
       }
    else if (spaceType === 'boardroom') {
    option.textContent = space.name;
    if (space.capacity) {
    option.textContent += ` (Capacity: ${space.capacity})`;
}
}

    spaceSelect.appendChild(option);
});

    spaceSelect.disabled = false;
    console.log('Spaces loaded:', result.length);
} else {
    alert(`No ${spaceType}s available on this floor`);
}
});

    // ==================== FORM VALIDATION ====================
    const form = document.getElementById('bookingForm');
    if (form) {
    form.addEventListener('submit', function(event) {
    if (!form.checkValidity()) {
    event.preventDefault();
    event.stopPropagation();
}
    form.classList.add('was-validated');
}, false);
}

    console.log('AJAX Booking Form initialized');
});

