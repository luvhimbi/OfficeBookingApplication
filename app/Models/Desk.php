<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Desk extends Model
{
    use HasFactory;

    protected $fillable = [
        'desk_number',
        'is_active',
        'campus_id',
        'building_id',
        'floor_id',
    ];


    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    // Relationships
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * A desk belongs to a specific floor.
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }


}
