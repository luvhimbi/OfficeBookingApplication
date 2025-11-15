<?php

namespace App\Http\Controllers;

use App\Models\Boardroom;
use App\Models\Building;
use App\Models\Campus;
use App\Models\Floor;
use Illuminate\Http\Request;

class BoardroomController extends Controller
{
    /**
     * Display a listing of the boardrooms.
     */
    public function index()
    {
        $boardrooms = Boardroom::with(['floor', 'building', 'campus'])->get();
        return view('admin.boardrooms.index', compact('boardrooms'));
    }

    /**
     * Show the form for creating a new boardroom.
     */
    public function create()
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();
        return view('admin.boardrooms.create', compact('campuses', 'buildings', 'floors'));
    }

    /**
     * Store a newly created boardroom in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
        ]);

        Boardroom::create($validated);


        return redirect()->route('boardrooms.index')
            ->with('success', 'Boardroom created successfully.');
    }
    /**
     * Show the form for editing the specified boardroom.
     */
    public function edit(Boardroom $boardroom)
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();
        return view('admin.boardrooms.edit', compact('boardroom', 'campuses', 'buildings', 'floors'));
    }

    /**
     * Update the specified boardroom in storage.
     */
    public function update(Request $request, Boardroom $boardroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
        ]);

        $boardroom->update($validated);

        return redirect()->route('boardrooms.index')
            ->with('success', 'Boardroom updated successfully.');
    }

    /**
     * Remove the specified boardroom from storage.
     */
    public function destroy(Boardroom $boardroom)
    {
        $boardroom->delete();

        return redirect()->route('boardrooms.index')
            ->with('success', 'Boardroom deleted successfully.');
    }
}
