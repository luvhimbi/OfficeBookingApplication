<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\FavoriteSpace;
use Illuminate\Support\Facades\DB;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        if ($booking->status === 'cancelled') {
            return;
        }

        $favorite = FavoriteSpace::where([
            'user_id'     => $booking->user_id,
            'campus_id'   => $booking->campus_id,
            'building_id' => $booking->building_id,
            'floor_id'    => $booking->floor_id,
            'space_type'  => $booking->space_type,
            'space_id'    => $booking->space_id,
        ])->first();

        if ($favorite) {
            $favorite->increment('booked_count');
        } else {
            FavoriteSpace::create([
                'user_id'     => $booking->user_id,
                'campus_id'   => $booking->campus_id,
                'building_id' => $booking->building_id,
                'floor_id'    => $booking->floor_id,
                'space_type'  => $booking->space_type,
                'space_id'    => $booking->space_id,
                'booked_count' => 1,
            ]);
        }

    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // If booking changed to cancelled, reduce count
        if ($booking->isDirty('status') && $booking->status === 'cancelled') {

            FavoriteSpace::where([
                'user_id'     => $booking->user_id,
                'campus_id'   => $booking->campus_id,
                'building_id' => $booking->building_id,
                'floor_id'    => $booking->floor_id,
                'space_type'  => $booking->space_type,
                'space_id'    => $booking->space_id,
            ])->decrement('booked_count');

            return;
        }
    }

    /**
     * Handle Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        FavoriteSpace::where([
            'user_id'     => $booking->user_id,
            'campus_id'   => $booking->campus_id,
            'building_id' => $booking->building_id,
            'floor_id'    => $booking->floor_id,
            'space_type'  => $booking->space_type,
            'space_id'    => $booking->space_id,
        ])->decrement('booked_count');
    }
}
