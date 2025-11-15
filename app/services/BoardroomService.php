<?php

namespace App\Services;

use App\Models\Boardroom;

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
}
