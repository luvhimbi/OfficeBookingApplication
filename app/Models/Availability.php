<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'available_id',
        'available_type',
        'date',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    /**
     * Get the owning available model (Desk or Boardroom).
     */
    public function available(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all bookings for this availability slot.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if this availability slot is fully booked.
     */
    public function isBooked(): bool
    {
        return $this->bookings()
            ->whereIn('status', ['booked', 'confirmed'])
            ->exists();
    }

    /**
     * Check if this availability slot is available for booking.
     */
    public function isAvailable(): bool
    {
        return $this->is_active && !$this->isBooked();
    }

    /**
     * Scope to get only active availability slots.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get availability for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope to get availability for a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope to get available (not booked) slots.
     */
    public function scopeAvailable($query)
    {
        return $query->active()
            ->whereDoesntHave('bookings', function ($q) {
                $q->whereIn('status', ['booked', 'confirmed']);
            });
    }
}
