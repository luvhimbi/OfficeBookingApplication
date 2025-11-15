<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\Boardroom;
use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = Availability::with('available')->latest()->paginate(10);

        return view('Admin.availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        $desks = Desk::all();
        $boardrooms = Boardroom::all();

        return view('Admin.availabilities.create', compact('desks', 'boardrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_type' => 'required|in:desk,boardroom',
            'resource_id' => 'required|integer',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Decide the polymorphic values
        $availableType = $validated['resource_type'] === 'desk'
            ? Desk::class
            : Boardroom::class;

        Availability::create([
            'available_id' => $validated['resource_id'],
            'available_type' => $availableType,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_active' => true
        ]);

        return redirect()->route('availabilities.index')
            ->with('success', 'Availability created successfully.');
    }

    public function edit(Availability $availability)
    {
        $desks = Desk::all();
        $boardrooms = Boardroom::all();

        return view('availabilities.edit', compact('availability', 'desks', 'boardrooms'));
    }

    public function update(Request $request, Availability $availability)
    {
        $validated = $request->validate([
            'resource_type' => 'required|in:desk,boardroom',
            'resource_id' => 'required|integer',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_active' => 'boolean'
        ]);

        $availableType = $validated['resource_type'] === 'desk'
            ? Desk::class
            : Boardroom::class;

        $availability->update([
            'available_id' => $validated['resource_id'],
            'available_type' => $availableType,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_active' => $validated['is_active'] ?? true
        ]);

        return redirect()->route('availabilities.index')
            ->with('success', 'Availability updated successfully.');
    }

    public function destroy(Availability $availability)
    {
        $availability->delete();

        return redirect()->route('availabilities.index')
            ->with('success', 'Availability deleted.');
    }
}
