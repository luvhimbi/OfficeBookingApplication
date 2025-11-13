<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    /**
     * Display a listing of the buildings.
     */
    public function index()
    {
        $buildings = Building::with('campus')->get();
        return view('admin.buildings.index', compact('buildings'));
    }

    /**
     * Show the form for creating a new building.
     */
    public function create()
    {
        $campuses = Campus::where('is_active', true)->get();
        return view('admin.buildings.create', compact('campuses'));
    }

    /**
     * Store a newly created building in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Building::create($validated);

        return redirect()->route('buildings.index')
            ->with('success', 'Building created successfully.');
    }

    /**
     * Display the specified building.
     */
    public function show(Building $building)
    {
        // Load the floors associated with this building
        $floors = $building->floors()->get();

        return view('admin.buildings.show', compact('building', 'floors'));
    }

    /**
     * Show the form for editing the specified building.
     */
    public function edit(Building $building)
    {
        $campuses = Campus::where('is_active', true)->get();
        return view('admin.buildings.edit', compact('building', 'campuses'));
    }

    /**
     * Update the specified building in storage.
     */
    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $building->update($validated);

        return redirect()->route('buildings.index')
            ->with('success', 'Building updated successfully.');
    }

    /**
     * Remove the specified building from storage.
     */
    public function destroy(Building $building)
    {
        $building->delete();

        return redirect()->route('buildings.index')
            ->with('success', 'Building deleted successfully.');
    }
}
