<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampusController extends Controller
{
    /**
     * Create a new controller instance.
     */


    /**
     * Display a listing of the campuses.
     */
    public function index()
    {
        $campuses = Campus::all();
        return view('admin.campuses.index', compact('campuses'));
    }

    /**
     * Show the form for creating a new campus.
     */
    public function create()
    {
        return view('admin.campuses.create');
    }

    /**
     * Store a newly created campus in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:campuses',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Campus::create($validated);

        return redirect()->route('campuses.index')
            ->with('success', 'Campus created successfully.');
    }

    /**
     * Display the specified campus.
     */
    public function show(Campus $campus)
    {
        return view('admin.campuses.show', compact('campus'));
    }

    /**
     * Show the form for editing the specified campus.
     */
    public function edit(Campus $campus)
    {
        return view('admin.campuses.edit', compact('campus'));
    }

    /**
     * Update the specified campus in storage.
     */
    public function update(Request $request, Campus $campus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:campuses,name,' . $campus->id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $campus->update($validated);

        return redirect()->route('campuses.index')
            ->with('success', 'Campus updated successfully.');
    }

    /**
     * Remove the specified campus from storage.
     */
    public function destroy(Campus $campus)
    {
        $campus->delete();

        return redirect()->route('campuses.index')
            ->with('success', 'Campus deleted successfully.');
    }
}
