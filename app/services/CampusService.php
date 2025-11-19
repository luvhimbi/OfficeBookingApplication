<?php

namespace App\Services;

use App\Models\Campus;

class CampusService
{
    /**
     * Get all campuses
     */
    public function getAll()
    {
        return Campus::all();
    }

    /**
     * Create a new campus
     */
    public function create(array $data)
    {
        return Campus::create($data);
    }

    /**
     * Update a campus
     */
    public function update(Campus $campus, array $data)
    {
        return $campus->update($data);
    }

    /**
     * Delete a campus
     */
    public function delete(Campus $campus)
    {
        return $campus->delete();
    }
    public function search(string $term)
    {
        return Campus::search($term)->paginate(10);
    }

}
