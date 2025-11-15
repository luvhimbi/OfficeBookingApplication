<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;
use App\Services\FloorService;

class FloorController extends Controller
{
    protected $floorService;

    public function __construct(FloorService $floorService)
    {
        $this->floorService = $floorService;
    }

    public function index(Request $request)
    {
        $buildingId = $request->building_id ?? null;
        $floors = $this->floorService->getAll($buildingId);

        $building = $buildingId ? Building::findOrFail($buildingId) : null;

        return view('admin.floors.index', compact('floors', 'building'));
    }

    public function create(Request $request)
    {
        $buildings = Building::where('is_active', true)->get();
        $selectedBuildingId = $request->building_id;
        $building = $selectedBuildingId ? Building::findOrFail($selectedBuildingId) : null;

        return view('admin.floors.create', compact('buildings', 'selectedBuildingId', 'building'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
        ]);

        $floor = $this->floorService->create($validated);

        if ($request->has('from_building')) {
            return redirect()->route('floors.index', ['building_id' => $floor->building_id])
                ->with('success', 'Floor created successfully.');
        }

        return redirect()->route('floors.index')->with('success', 'Floor created successfully.');
    }

    public function show(Floor $floor)
    {
        return view('admin.floors.show', compact('floor'));
    }

    public function edit(Floor $floor)
    {
        $buildings = Building::where('is_active', true)->get();
        return view('admin.floors.edit', compact('floor', 'buildings'));
    }

    public function update(Request $request, Floor $floor)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
        ]);

        $this->floorService->update($floor, $validated);

        return redirect()->route('floors.index')->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor)
    {
        $this->floorService->delete($floor);

        return redirect()->route('floors.index')->with('success', 'Floor deleted successfully.');
    }
}
