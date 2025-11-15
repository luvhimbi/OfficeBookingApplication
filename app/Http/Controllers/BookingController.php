<?php

namespace App\Http\Controllers;

use App\Models\Boardroom;
use App\Models\Booking;
use App\Models\Building;
use App\Models\Campus;
use App\Models\Desk;
use App\Models\Floor;
use App\Services\EmailService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    protected $emailService;
    protected  $notificationService;

    public function __construct(EmailService $emailService,NotificationService $notificationService)
    {
        $this->emailService = $emailService;
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        $bookings = Booking::with(['user', 'campus', 'building', 'boardroom' ,'floor'])
            ->latest()
            ->get();

        return view('Employee.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'campus', 'building', 'boardroom', 'desk', 'floor']);
        return view('Employee.bookings.show', compact('booking'));
    }


    public function create()
    {
        $campuses = Campus::orderBy('name')->get();

        return view('employee.bookings.create', compact('campuses'));
    }

    /**
     * Get buildings for a specific campus (AJAX)
     */
    public function getBuildings($campusId)
    {
        try {
            $buildings = Building::where('campus_id', $campusId)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $buildings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading buildings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calendarView()
    {
        $bookings = \App\Models\Booking::with(['desk', 'boardroom', 'user'])
            ->where('status', 'booked')
            ->get();

        $events = $bookings->map(function($booking) {
            $title = $booking->space_type === 'desk'
                ? 'Desk: ' . ($booking->desk->desk_number ?? 'N/A')
                : 'Boardroom: ' . ($booking->boardroom->name ?? 'N/A');

            $title .= ' (' . $booking->user->firstname . ' ' . $booking->user->lastname . ')';

            // Ensure proper date/time format
            $startDateTime = $booking->date . 'T' . $this->formatTime($booking->start_time);
            $endDateTime = $booking->date . 'T' . $this->formatTime($booking->end_time);

            return [
                'title' => $title,
                'start' => $startDateTime,
                'end' => $endDateTime,
                'color' => $booking->space_type === 'desk' ? '#4e73df' : '#1cc88a',
                'allDay' => false, // Explicitly set to false for timed events
            ];
        });

        return view('Employee.bookings.calendar', compact('events'));
    }

// Helper method to format time
    private function formatTime($time)
    {
        // If time is already in correct format, return as is
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            return $time;
        }

        // If time is in 12-hour format, convert to 24-hour
        return date('H:i:s', strtotime($time));
    }

    /**
     * Get floors for a specific building (AJAX)
     */
    public function getFloors($buildingId)
    {
        try {
            $floors = Floor::where('building_id', $buildingId)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $floors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading floors: ' . $e->getMessage()
            ], 500);
        }
    } /**
 * Get spaces (desks or boardrooms) for a specific floor (AJAX)
 */
    public function getSpaces($floorId, $type)
    {
        try {
            $spaces = [];

            if ($type === 'desk') {
                $spaces = Desk::where('floor_id', $floorId)
                    ->where('is_active', true)
                    ->orderBy('desk_number')
                    ->get(['id', 'desk_number', 'name', 'is_active']);
            } elseif ($type === 'boardroom') {
                $spaces = Boardroom::where('floor_id', $floorId)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(['id', 'name', 'capacity', 'is_active']);
            }

            return response()->json([
                'success' => true,
                'data' => $spaces,
                'type' => $type
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading spaces: ' . $e->getMessage()
            ], 500);
        }
    }


    // Store booking
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
            'space_type' => 'required|in:desk,boardroom',
            'space_id' => 'required', // will validate below
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Check if space exists
        if ($validated['space_type'] === 'desk') {
            $space = Desk::where('id', $validated['space_id'])->firstOrFail();
        } else {
            $space = Boardroom::where('id', $validated['space_id'])->firstOrFail();
        }

        // Prevent overlapping bookings
        $conflict = Booking::where('space_type', $validated['space_type'])
            ->where('space_id', $validated['space_id'])
            ->where(function($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function($query) use ($validated){
                        $query->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['space_id' => 'This space is already booked for the selected time.'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'campus_id' => $validated['campus_id'],
            'building_id' => $validated['building_id'],
            'floor_id' => $validated['floor_id'],
            'space_type' => $validated['space_type'],
            'space_id' => $validated['space_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'booked',
        ]);

        $this->emailService->sendBookingConfirmation($booking);
        $this->notificationService->createNotification(
            $booking->user_id,
            'Booking Confirmed',
            "Your booking for {$booking->space_type} on {$booking->date} from {$booking->start_time} to {$booking->end_time} is confirmed.",
            'success'
        );
        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        // Only the owner or an admin can cancel
        if ($booking->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'You are not authorized to cancel this booking.');
        }

        // Prevent duplicate cancellation
        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('info', 'This booking is already cancelled.');
        }

        // Update booking status
        $booking->update(['status' => 'cancelled']);

        $this->emailService->sendBookingCancellation($booking);
        $this->notificationService->createNotification(
            $booking->user_id,
            'Booking Cancelled',
            "Your booking for {$booking->space_type} on {$booking->date} has been cancelled.",
            'error'
        );

        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }

}
