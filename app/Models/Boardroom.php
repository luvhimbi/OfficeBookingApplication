<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Boardroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'description',
        'is_active',
        'campus_id',
        'building_id',
        'floor_id',
    ];
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }


    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * A boardroom belongs to a specific floor.
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    /**
     * Get all availabilities for this desk.
     */
    public function availabilities(): MorphMany
    {
        return $this->morphMany(Availability::class, 'available');
    }
    /**
     * Get all bookings for this desk.
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}
