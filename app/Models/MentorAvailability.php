<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class MentorAvailability extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'mentor_profile_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_recurring',
        'effective_date',
        'expiry_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_recurring' => 'boolean',
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the mentor profile that owns the availability slot.
     */
    public function mentorProfile(): BelongsTo
    {
        return $this->belongsTo(MentorProfile::class);
    }

    /**
     * Scope a query to only include current and future availability slots.
     */
    public function scopeCurrent($query)
    {
        $today = Carbon::today();
        return $query->where(function ($query) use ($today) {
            $query->where('is_recurring', true)
                  ->orWhere(function ($query) use ($today) {
                      $query->where('effective_date', '<=', $today)
                            ->where(function ($query) use ($today) {
                                $query->whereNull('expiry_date')
                                      ->orWhere('expiry_date', '>=', $today);
                            });
                  });
        });
    }

    /**
     * Scope a query to only include availability slots for a specific day of the week.
     */
    public function scopeDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', strtolower($dayOfWeek));
    }

    /**
     * Check if the availability slot overlaps with a given time range.
     */
    public function overlaps($startTime, $endTime): bool
    {
        $slotStart = Carbon::parse($this->start_time);
        $slotEnd = Carbon::parse($this->end_time);
        $rangeStart = Carbon::parse($startTime);
        $rangeEnd = Carbon::parse($endTime);

        return ($rangeStart < $slotEnd && $rangeEnd > $slotStart);
    }

    /**
     * Format the start time for display.
     */
    public function getFormattedStartTimeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }

    /**
     * Format the end time for display.
     */
    public function getFormattedEndTimeAttribute(): string
    {
        return Carbon::parse($this->end_time)->format('H:i');
    }

    /**
     * Get the day name.
     */
    public function getDayNameAttribute(): string
    {
        return ucfirst($this->day_of_week);
    }
}
