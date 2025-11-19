<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;
use App\Services\CampusService;

class CampusController extends Controller
{
    protected $campusService;

    public function __construct(CampusService $campusService)
    {
        $this->campusService = $campusService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            // Use Laravel Scout search
            $campuses = $this->campusService->search($search);
        } else {
            $campuses = $this->campusService->getAll();
        }

        return view('admin.campuses.index', compact('campuses', 'search'));
    }

    public function create()
    {
        return view('admin.campuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:campuses',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $this->campusService->create($validated);

        return redirect()->route('campuses.index')
            ->with('success', 'Campus created successfully.');
    }

    public function show(Campus $campus)
    {
        return view('admin.campuses.show', compact('campus'));
    }

    public function edit(Campus $campus)
    {
        return view('admin.campuses.edit', compact('campus'));
    }

    public function update(Request $request, Campus $campus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:campuses,name,' . $campus->id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $this->campusService->update($campus, $validated);

        return redirect()->route('campuses.index')
            ->with('success', 'Campus updated successfully.');
    }

    public function destroy(Campus $campus)
    {
        $this->campusService->delete($campus);

        return redirect()->route('campuses.index')
            ->with('success', 'Campus deleted successfully.');
    }
}
