<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FloorController extends Controller
{
    /**
     * Display a listing of the floors.
     */
    public function index(Request $request)
    {
        if ($request->has('building_id')) {
            $building = Building::findOrFail($request->building_id);
            $floors = Floor::where('building_id', $building->id)->with('building')->get();
            return view('admin.floors.index', compact('floors', 'building'));
        } else {
            $floors = Floor::with('building')->get();
            return view('admin.floors.index', compact('floors'));
        }
    }

    /**
     * Show the form for creating a new floor.
     */
    public function create(Request $request)
    {
        $buildings = Building::where('is_active', true)->get();
        $selectedBuildingId = $request->building_id;

        if ($selectedBuildingId) {
            $building = Building::findOrFail($selectedBuildingId);
            return view('admin.floors.create', compact('buildings', 'selectedBuildingId', 'building'));
        }

        return view('admin.floors.create', compact('buildings'));
    }

    /**
     * Store a newly created floor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
        ]);

        $floor = Floor::create($validated);

        // If we're coming from a specific building context, redirect back to that building's floors
        if ($request->has('from_building')) {
            return redirect()->route('floors.index', ['building_id' => $floor->building_id])
                ->with('success', 'Floor created successfully.');
        }

        return redirect()->route('floors.index')
            ->with('success', 'Floor created successfully.');
    }

    /**
     * Display the specified floor.
     */
    public function show(Floor $floor)
    {
        return view('admin.floors.show', compact('floor'));
    }

    /**
     * Show the form for editing the specified floor.
     */
    public function edit(Floor $floor)
    {
        $buildings = Building::where('is_active', true)->get();
        return view('admin.floors.edit', compact('floor', 'buildings'));
    }

    /**
     * Update the specified floor in storage.
     */
    public function update(Request $request, Floor $floor)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
        ]);

        $floor->update($validated);

        return redirect()->route('floors.index')
            ->with('success', 'Floor updated successfully.');
    }

    /**
     * Remove the specified floor from storage.
     */
    public function destroy(Floor $floor)
    {
        $floor->delete();

        return redirect()->route('floors.index')
            ->with('success', 'Floor deleted successfully.');
    }
}
