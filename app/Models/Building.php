<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Building extends Model
{
    use HasFactory,Searchable;

    protected $fillable = [
        'campus_id',
        'name',
        'is_active'
    ];

    // Relationship: Building belongs to one campus
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    // Relationship: Building has many floors
    public function floors()
    {
        return $this->hasMany(Floor::class);
    }
    public function toSearchableArray()
    {
        return [
            'name'   => $this->name,
            'campus' => $this->campus ? $this->campus->name : null,
        ];
    }

}
