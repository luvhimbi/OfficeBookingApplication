<?php

namespace App\Services;

use App\Models\Desk;

class DeskService
{
    /**
     * Get all desks with relationships
     */
    public function all()
    {
        return Desk::with('floor', 'building', 'campus')->get();
    }

    /**
     * Create a new desk
     */
    public function create(array $data)
    {
        return Desk::create($data);
    }

    /**
     * Update an existing desk
     */
    public function update(Desk $desk, array $data)
    {
        return $desk->update($data);
    }

    /**
     * Delete a desk
     */
    public function delete(Desk $desk)
    {
        return $desk->delete();
    }
}
