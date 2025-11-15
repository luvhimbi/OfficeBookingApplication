<?php

namespace App\Services;

use App\Models\Floor;

class FloorService
{
    /**
     * Get all floors optionally by building
     */
    public function getAll($buildingId = null)
    {
        $query = Floor::with('building');

        if ($buildingId) {
            $query->where('building_id', $buildingId);
        }

        return $query->get();
    }

    /**
     * Create a new floor
     */
    public function create(array $data)
    {
        return Floor::create($data);
    }

    /**
     * Update an existing floor
     */
    public function update(Floor $floor, array $data)
    {
        return $floor->update($data);
    }

    /**
     * Delete a floor
     */
    public function delete(Floor $floor)
    {
        return $floor->delete();
    }
}
