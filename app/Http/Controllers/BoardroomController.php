<?php

namespace App\Http\Controllers;

use App\Models\Boardroom;
use App\Models\Building;
use App\Models\Campus;
use App\Models\Floor;
use Illuminate\Http\Request;
use App\Services\BoardroomService;

class BoardroomController extends Controller
{
    protected $boardroomService;

    public function __construct(BoardroomService $boardroomService)
    {
        $this->boardroomService = $boardroomService;
    }

    public function index()
    {
        $boardrooms = $this->boardroomService->getAllBoardrooms();
        return view('admin.boardrooms.index', compact('boardrooms'));
    }

    public function create()
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();
        return view('admin.boardrooms.create', compact('campuses', 'buildings', 'floors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
        ]);

        $this->boardroomService->createBoardroom($validated);

        return redirect()->route('boardrooms.index')
            ->with('success', 'Boardroom created successfully.');
    }

    public function edit(Boardroom $boardroom)
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();
        return view('admin.boardrooms.edit', compact('boardroom', 'campuses', 'buildings', 'floors'));
    }

    public function update(Request $request, Boardroom $boardroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
        ]);

        $this->boardroomService->updateBoardroom($boardroom, $validated);

        return redirect()->route('boardrooms.index')
            ->with('success', 'Boardroom updated successfully.');
    }

    public function destroy(Boardroom $boardroom)
    {
        $this->boardroomService->deleteBoardroom($boardroom);

        return redirect()->route('boardrooms.index')
            ->with('success', 'Boardroom deleted successfully.');
    }
}
