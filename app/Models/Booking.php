<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Booking extends Model
{
    use HasFactory,Searchable;

    protected $fillable = [
        'user_id',
        'campus_id',
        'building_id',
        'floor_id',
        'space_type',
        'space_id',
        'date',
        'start_time',
        'end_time',
        'status',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'date' => 'date:Y-m-d',
        'start_time' => 'string',
        'end_time' => 'string',
    ];
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
    public function desk() { return $this->belongsTo(Desk::class, 'space_id'); }
    public function boardroom() { return $this->belongsTo(Boardroom::class, 'space_id'); }


    // Optional: dynamic accessor to get the correct space
    public function getSpaceAttribute()
    {
        if ($this->space_type === 'desk') {
            return $this->desk;
        } elseif ($this->space_type === 'boardroom') {
            return $this->boardroom;
        }
        return null;
    }
    public function toSearchableArray()
    {
        // Determine the related space name based on space_type
        $spaceName = null;

        if ($this->space_type === 'desk' && $this->desk) {
            $spaceName = $this->desk->desk_number; // or any relevant field
        } elseif ($this->space_type === 'boardroom' && $this->boardroom) {
            $spaceName = $this->boardroom->name; // or any relevant field
        }

        return [
            'user' => $this->user ? $this->user->name : null,
            'campus' => $this->campus ? $this->campus->name : null,
            'building' => $this->building ? $this->building->name : null,
            'floor' => $this->floor ? $this->floor->name : null,
            'space_type' => $this->space_type,
            'space_name' => $spaceName,
            'date' => $this->date ? $this->date->toDateString() : null,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
        ];
    }


}
