<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Campus;
use App\Models\Desk;
use App\Models\Floor;
use Illuminate\Http\Request;

class DeskController extends Controller
{
    /**
     * Display a listing of the desks.
     */
    public function index()
    {
        $desks = Desk::with('floor','building','campus')->get();
        return view('admin.desks.index', compact('desks'));
    }

    /**
     * Show the form for creating a new desk.
     */
    public function create()
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();
        return view('admin.desks.create', compact('floors','buildings','campuses'));
    }

    /**
     * Store a newly created desk in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'desk_number' => 'required|string|max:255|unique:desks',
            'floor_id' => 'required|exists:floors,id',
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
        ]);

        Desk::create($validated);

        return redirect()->route('desks.index')
            ->with('success', 'Desk created successfully.');
    }

    /**
     * Show the form for editing the specified desk.
     */
    public function edit(Desk $desk)
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();
        return view('admin.desks.edit', compact('desk', 'floors', 'buildings', 'campuses'));
    }

    /**
     * Update the specified desk in storage.
     */
    public function update(Request $request, Desk $desk)
    {
        $validated = $request->validate([
            'desk_number' => 'required|string|max:255|unique:desks,desk_number,' . $desk->id,
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
        ]);

        $desk->update($validated);

        return redirect()->route('desks.index')
            ->with('success', 'Desk updated successfully.');
    }

    /**
     * Remove the specified desk from storage.
     */
    public function destroy(Desk $desk)
    {
        $desk->delete();

        return redirect()->route('desks.index')
            ->with('success', 'Desk deleted successfully.');
    }
}
