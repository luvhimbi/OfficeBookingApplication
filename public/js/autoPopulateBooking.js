document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded');

    // Get URL parameter easily
    function getParam(key) {
        return new URLSearchParams(window.location.search).get(key);
    }

    const campusId = getParam('campus_id');
    const buildingId = getParam('building_id');
    const floorId = getParam('floor_id');
    const spaceType = getParam('space_type');
    const spaceId = getParam('space_id');

    console.log('URL Params:', {
        campusId,
        buildingId,
        floorId,
        spaceType,
        spaceId
    });

    // Wait until element exists
    function waitFor(selector) {
        return new Promise(resolve => {
            const check = setInterval(() => {
                const el = document.querySelector(selector);
                if (el) {
                    clearInterval(check);
                    resolve(el);
                }
            }, 200);
        });
    }

    // Trigger change event
    function fireChange(el) {
        el.dispatchEvent(new Event('change', { bubbles: true }));
    }

    // Main logic
    async function autoPopulate() {
        if (!campusId) return;

        console.log('Auto Fill Started');

        // 1. Campus
        const campusSelect = await waitFor('#campus_id');
        campusSelect.value = campusId;
        fireChange(campusSelect);
        console.log('Campus set');

        // slowdown to allow Ajax/Livewire etc to update
        await new Promise(r => setTimeout(r, 800));

        // 2. Building
        if (buildingId) {
            const buildingSelect = await waitFor('#building_id');
            buildingSelect.value = buildingId;
            fireChange(buildingSelect);
            console.log('Building set');
            await new Promise(r => setTimeout(r, 800));
        }

        // 3. Floor
        if (floorId) {
            const floorSelect = await waitFor('#floor_id');
            floorSelect.value = floorId;
            fireChange(floorSelect);
            console.log('Floor set');
            await new Promise(r => setTimeout(r, 800));
        }

        // 4. Space Type
        if (spaceType) {
            const typeSelect = await waitFor('#space_type');
            typeSelect.value = spaceType;
            fireChange(typeSelect);
            console.log('Space Type set');
            await new Promise(r => setTimeout(r, 800));
        }

        // 5. Space ID (desk or boardroom)
        if (spaceId) {
            const spaceSelect = await waitFor('#space_id');

            // enable select
            spaceSelect.disabled = false;

            // wait until options are populated
            await new Promise(resolve => {
                const check = setInterval(() => {
                    if (spaceSelect.options.length > 1) {
                        clearInterval(check);
                        resolve();
                    }
                }, 200);
            });

            spaceSelect.value = spaceId;
            fireChange(spaceSelect);
        }
    }

    autoPopulate();
});
