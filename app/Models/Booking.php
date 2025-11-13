<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campus_id',
        'building_id',
        'floor_id',
        'space_type',
        'space_id',
        'start_time',
        'end_time',
        'status',
        'is_recurring',
        'recurring_day',
        'recurring_until',
        'parent_booking_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function boardroom()
    {
        return $this->belongsTo(Boardroom::class);
    }
    public function desk()
    {
        return $this->belongsTo(Desk::class);
    }

    public function space()
    {
        return $this->morphTo(__FUNCTION__, 'space_type', 'space_id');
    }
}
