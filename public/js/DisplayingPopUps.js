document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('bookingForm');
    const spaceType = document.getElementById('space_type');
    const spaceId = document.getElementById('space_id');
    const dateField = document.getElementById('date');
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');

    const suggestionWrap = document.getElementById('slotSuggestionWrapper');
    const suggestionBox = document.getElementById('slotSuggestions');

    let selectedSlots = []; // Keep track of selected slots

    // ------------------------
    // Slot selection functions
    // ------------------------
    function canLoadAvailability() {
        return (
            spaceType.value !== "" &&
            spaceId.value !== "" &&
            dateField.value !== "" &&
            !spaceId.disabled
        );
    }

    function loadAvailability() {
        if (!canLoadAvailability()) {
            suggestionWrap.style.display = "none";
            return;
        }

        fetch(`/bookings/availability?space_type=${spaceType.value}&space_id=${spaceId.value}&date=${dateField.value}`)
            .then(res => {
                if (!res.ok) throw new Error("Network error");
                return res.json();
            })
            .then(data => {

                suggestionBox.innerHTML = "";
                selectedSlots = []; // Reset selections on reload

                if (data.recommended.length === 0) {
                    suggestionWrap.style.display = "none";
                    Swal.fire({
                        icon: "error",
                        title: "No Available Slots",
                        text: "This space is fully booked for the selected date."
                    });
                    startTime.value = '';
                    endTime.value = '';
                    return;
                }

                suggestionWrap.style.display = "block";

                data.recommended.forEach(slot => {
                    const chip = document.createElement("button");

                    chip.type = "button"; // <-- Prevents triggering form submit
                    chip.className = "btn btn-outline-primary rounded-pill px-3 py-1 m-1";
                    chip.textContent = `${slot.start} - ${slot.end}`;

                    chip.onclick = function () {
                        const slotKey = slot.start + '-' + slot.end;

                        // Toggle selection
                        if (selectedSlots.includes(slotKey)) {
                            selectedSlots = selectedSlots.filter(s => s !== slotKey);
                            chip.classList.remove('active');
                        } else {
                            selectedSlots.push(slotKey);
                            chip.classList.add('active');
                        }

                        // Update start_time and end_time automatically
                        if (selectedSlots.length > 0) {
                            const times = selectedSlots
                                .map(s => s.split('-'))
                                .flat()
                                .map(t => t.trim())
                                .sort();

                            startTime.value = times[0];
                            endTime.value = times[times.length - 1];
                        } else {
                            startTime.value = '';
                            endTime.value = '';
                        }
                    };

                    suggestionBox.appendChild(chip);
                });

            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Failed to load availability.", "error");
            });
    }

    spaceId.addEventListener('change', loadAvailability);
    dateField.addEventListener('change', loadAvailability);
    spaceType.addEventListener('change', loadAvailability);

    // ------------------------
    // Form submit confirmation
    // ------------------------
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // HTML5 validation
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to confirm this booking?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, book it",
            cancelButtonText: "No, cancel",
            reverseButtons: true,
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-secondary"
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading while submitting
                Swal.fire({
                    title: "Processing...",
                    text: "Please wait while we complete your booking",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading()
                });

                setTimeout(() => form.submit(), 300);
            }
        });
    });

});
