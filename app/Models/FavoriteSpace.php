<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteSpace extends Model
{
    use HasFactory;

    protected $table = 'favorite_spaces';

    protected $fillable = [
        'user_id',
        'campus_id',
        'building_id',
        'floor_id',
        'space_type',
        'space_id',
        'booked_count'
    ];

    /*
     * Relationships
     */

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
}
