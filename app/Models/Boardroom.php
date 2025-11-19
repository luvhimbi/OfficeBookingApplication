<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;

class Boardroom extends Model
{
    use HasFactory , Searchable;

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
    public function toSearchableArray()
    {
        return [
            'name'   => $this->name,
            'capacity'=>$this->capacity,
            'description'=>$this->description,
            'campus' => $this->campus ? $this->campus->name : null,
            'building'=>$this->building ? $this->building->name : null,
            'floor'=>$this->floor ? $this->floor->name : null,
        ];
    }

}
