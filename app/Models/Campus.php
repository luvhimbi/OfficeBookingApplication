<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Campus extends Model
{
    use HasFactory,Searchable;

    protected $fillable = [
        'name',
        'address',
        'city',
        'is_active'
    ];

    // Relationship: Campus has many buildings
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    /**
     * Get the indexable data for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'name'    => $this->name,
            'address' => $this->address,
            'city'    => $this->city,
        ];
    }
}
