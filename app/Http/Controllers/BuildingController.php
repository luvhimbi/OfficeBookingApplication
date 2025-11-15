<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Campus;
use Illuminate\Http\Request;
use App\Services\BuildingService;

class BuildingController extends Controller
{
    protected $buildingService;

    public function __construct(BuildingService $buildingService)
    {
        $this->buildingService = $buildingService;
    }

    public function index()
    {
        $buildings = $this->buildingService->getAll();
        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        $campuses = Campus::where('is_active', true)->get();
        return view('admin.buildings.create', compact('campuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $this->buildingService->create($validated);

        return redirect()->route('buildings.index')
            ->with('success', 'Building created successfully.');
    }

    public function show(Building $building)
    {
        $floors = $building->floors()->get();
        return view('admin.buildings.show', compact('building', 'floors'));
    }

    public function edit(Building $building)
    {
        $campuses = Campus::where('is_active', true)->get();
        return view('admin.buildings.edit', compact('building', 'campuses'));
    }

    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $this->buildingService->update($building, $validated);

        return redirect()->route('buildings.index')
            ->with('success', 'Building updated successfully.');
    }

    public function destroy(Building $building)
    {
        $this->buildingService->delete($building);

        return redirect()->route('buildings.index')
            ->with('success', 'Building deleted successfully.');
    }
}
