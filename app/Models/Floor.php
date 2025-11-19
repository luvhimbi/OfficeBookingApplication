<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Floor extends Model
{

    use HasFactory,Searchable;

    protected $fillable = [
        'building_id',
        'name'
    ];

    // Relationship: Each floor belongs to a specific building
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // Relationship: Floor has many desks
    public function desks()
    {
        return $this->hasMany(Desk::class);
    }

    // Relationship: Floor has many boardrooms
    public function boardrooms()
    {
        return $this->hasMany(Boardroom::class);
    }
    public function toSearchableArray()
    {
        return [
            'building' => $this->building ? $this->building->name : null,
            'name'   => $this->name,
        ];
    }
}
