<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'availability_id',
        'campus_id',
        'building_id',
        'floor_id',
        'space_type',
        'space_id',
        'booking_date',
        'status',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
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

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function space()
    {
        return $this->morphTo(__FUNCTION__, 'space_type', 'space_id');
    }
}
