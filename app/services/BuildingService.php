<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Campus;

class BuildingService
{
    /**
     * Get all buildings with campus
     */
    public function getAll()
    {
        return Building::with('campus')->get();
    }

    /**
     * Create a new building
     */
    public function create(array $data)
    {
        return Building::create($data);
    }

    /**
     * Update an existing building
     */
    public function update(Building $building, array $data)
    {
        return $building->update($data);
    }

    /**
     * Delete a building
     */
    public function delete(Building $building)
    {
        return $building->delete();
    }
    public function search(string $term)
    {
        return Building::search($term)->paginate(10);
    }
}
