<?php

namespace App\Services;

use App\Models\Boardroom;
use App\Models\Building;

class BoardroomService
{
    /**
     * Get all boardrooms with related campus, building, and floor.
     */
    public function getAllBoardrooms()
    {
        return Boardroom::with(['floor', 'building', 'campus'])->get();
    }

    /**
     * Create a new boardroom.
     */
    public function createBoardroom(array $data): Boardroom
    {
        return Boardroom::create($data);
    }

    /**
     * Update an existing boardroom.
     */
    public function updateBoardroom(Boardroom $boardroom, array $data): Boardroom
    {
        $boardroom->update($data);
        return $boardroom;
    }

    /**
     * Delete a boardroom.
     */
    public function deleteBoardroom(Boardroom $boardroom): void
    {
        $boardroom->delete();
    }
    public function search(string $term)
    {
        return Boardroom::search($term)->paginate(10);
    }
}
