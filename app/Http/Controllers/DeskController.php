<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\Building;
use App\Models\Campus;
use App\Models\Floor;
use Illuminate\Http\Request;
use App\Services\DeskService;

class DeskController extends Controller
{
    protected $deskService;

    public function __construct(DeskService $deskService)
    {
        $this->deskService = $deskService;
    }

    public function index()
    {
        $desks = $this->deskService->all();
        return view('admin.desks.index', compact('desks'));
    }

    public function create()
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();
        return view('admin.desks.create', compact('floors', 'buildings', 'campuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desk_number' => 'required|string|max:255|unique:desks',
            'floor_id' => 'required|exists:floors,id',
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
        ]);

        $this->deskService->create($validated);

        return redirect()->route('desks.index')->with('success', 'Desk created successfully.');
    }

    public function edit(Desk $desk)
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();
        return view('admin.desks.edit', compact('desk', 'floors', 'buildings', 'campuses'));
    }

    public function update(Request $request, Desk $desk)
    {
        $validated = $request->validate([
            'desk_number' => 'required|string|max:255|unique:desks,desk_number,' . $desk->id,
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
        ]);

        $this->deskService->update($desk, $validated);

        return redirect()->route('desks.index')->with('success', 'Desk updated successfully.');
    }

    public function destroy(Desk $desk)
    {
        $this->deskService->delete($desk);

        return redirect()->route('desks.index')->with('success', 'Desk deleted successfully.');
    }
}
