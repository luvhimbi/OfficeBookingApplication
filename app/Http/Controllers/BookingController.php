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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    protected $emailService;
    protected  $notificationService;

    public function __construct(EmailService $emailService,NotificationService $notificationService)
    {
        $this->emailService = $emailService;
        $this->notificationService = $notificationService;
    }
    public function index(Request $request)
    {

        $search = $request->input('search');
        if ($search) {
            $bookings= $this->search($search);
        }
        else {

            $query = Booking::with(['user', 'campus', 'building', 'floor', 'desk', 'boardroom'])
                ->latest();

            // If the logged-in user is NOT admin, show only their bookings
            if (auth()->user()->role !== 'admin') {
                $query->where('user_id', auth()->id());
            }

            $bookings = $query->get();

            // Optional debug logging
            foreach ($bookings as $booking) {
                $spaceName = $booking->space_type === 'desk'
                    ? ($booking->desk->desk_number ?? 'N/A')
                    : ($booking->boardroom->name ?? 'N/A');

                \Log::info("Booking {$booking->id} space:", [
                    'space_type' => $booking->space_type,
                    'space_name' => $spaceName
                ]);
            }
        }

        return view('Employee.bookings.index', compact('bookings','search'));
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
                Log::info("Fetching desks for floor $floorId");

                $spaces = Desk::where('floor_id', $floorId)
                    ->where('is_active', true)
                    ->orderBy('desk_number')
                    ->get(['id', 'desk_number']);

                Log::info("Desks fetched:", $spaces->toArray());
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




    public function store(Request $request)
    {
        $validated = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
            'space_type' => 'required|in:desk,boardroom',
            'space_id' => 'required',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Combine date + time for DB comparison
        $startDateTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $endDateTime = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

        // Check if space exists
        $spaceModel = $validated['space_type'] === 'desk' ? Desk::class : Boardroom::class;
        $space = $spaceModel::findOrFail($validated['space_id']);

        // Prevent overlapping bookings EXCLUDING cancelled ones
        $conflict = Booking::where('space_type', $validated['space_type'])
            ->where('space_id', $validated['space_id'])
            ->whereDate('date', $validated['date'])
            ->where('status', '!=', 'cancelled') // exclude cancelled bookings
            ->where(function($q) use ($startDateTime, $endDateTime) {
                $q->where(function($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_time', '<', $endDateTime->format('H:i'))
                        ->where('end_time', '>', $startDateTime->format('H:i'));
                });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['space_id' => 'This space is already booked for the selected time.'])->withInput();
        }

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'campus_id' => $validated['campus_id'],
            'building_id' => $validated['building_id'],
            'floor_id' => $validated['floor_id'],
            'space_type' => $validated['space_type'],
            'space_id' => $validated['space_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'booked',
        ]);

        // Notifications
        $this->emailService->sendBookingConfirmation($booking);
        $this->notificationService->createNotification(
            $booking->user_id,
            'Booking Confirmed',
            "Your booking for {$booking->space_type} on " . Carbon::parse($booking->date)->format('Y-m-d') .
            " from {$booking->start_time} to {$booking->end_time} is confirmed.",
            'success'
        );

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    public function availability(Request $request)
    {
        $request->validate([
            'space_id' => 'required|integer',
            'space_type' => 'required|in:desk,boardroom',
            'date' => 'required|date'
        ]);

        $spaceId = $request->space_id;
        $spaceType = $request->space_type;
        $date = $request->date;

        // Fetch bookings EXCLUDING cancelled ones
        $bookings = Booking::where('space_type', $spaceType)
            ->where('space_id', $spaceId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled') // ignore cancelled bookings
            ->orderBy('start_time')
            ->get(['start_time', 'end_time']);

        $taken = [];
        foreach ($bookings as $b) {
            $taken[] = [
                'start' => Carbon::parse($b->start_time)->format('H:i'),
                'end'   => Carbon::parse($b->end_time)->format('H:i'),
            ];
        }

        // Generate available slots
        $possible = [];
        $start = Carbon::createFromTime(8, 0);
        $end   = Carbon::createFromTime(17, 0);

        while ($start->lt($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addHour();

            $isTaken = false;

            foreach ($taken as $t) {
                $tStart = Carbon::parse($t['start']);
                $tEnd   = Carbon::parse($t['end']);

                if ($slotStart->lt($tEnd) && $slotEnd->gt($tStart)) {
                    $isTaken = true;
                    break;
                }
            }

            if (!$isTaken) {
                $possible[] = [
                    'start' => $slotStart->format('H:i'),
                    'end'   => $slotEnd->format('H:i')
                ];
            }

            $start->addHour();
        }

        return response()->json([
            'booked' => $taken,
            'recommended' => $possible
        ]);
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
            "Your booking for {$booking->space_type} on " . Carbon::parse($booking->date)->format('Y-m-d') . " has been cancelled.",
            'error'
        );
        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }
    public function edit(Booking $booking)
    {
        $campuses = Campus::all();
        $buildings = Building::all();
        $floors = Floor::all();

        // Determine the correct space type
        $spaces = $booking->space_type === 'desk'
            ? Desk::all()
            : Boardroom::all();

        return view('Employee.bookings.edit', compact('booking', 'campuses', 'buildings', 'floors', 'spaces'));
    }


    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'building_id' => 'required|exists:buildings,id',
            'floor_id' => 'required|exists:floors,id',
            'space_type' => 'required|in:desk,boardroom',
            'space_id' => 'required',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $startDateTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $endDateTime = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);

        // Conflict check excluding cancelled bookings and current booking
        $conflict = Booking::where('space_type', $validated['space_type'])
            ->where('space_id', $validated['space_id'])
            ->whereDate('date', $validated['date'])
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $booking->id) // exclude current booking
            ->where(function($q) use ($startDateTime, $endDateTime) {
                $q->where(function($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_time', '<', $endDateTime->format('H:i'))
                        ->where('end_time', '>', $startDateTime->format('H:i'));
                });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['space_id' => 'This space is already booked for the selected time.'])->withInput();
        }

        // Update the booking
        $booking->update([
            'campus_id' => $validated['campus_id'],
            'building_id' => $validated['building_id'],
            'floor_id' => $validated['floor_id'],
            'space_type' => $validated['space_type'],
            'space_id' => $validated['space_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        // Optional: send notification/email
        $this->emailService->sendBookingConfirmation($booking);
        $this->notificationService->createNotification(
            $booking->user_id,
            'Booking Updated',
            "Your booking has been updated for {$booking->space_type} on {$booking->date} from {$booking->start_time} to {$booking->end_time}.",
            'info'
        );

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
    }

    public function search(string $term)
    {
        return Booking::search($term)->paginate(10);
    }

}
