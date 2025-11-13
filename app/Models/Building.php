<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

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
}
