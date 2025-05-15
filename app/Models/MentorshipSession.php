<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MentorshipSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'mentor_profile_id',
        'student_id',
        'title',
        'description',
        'goals',
        'scheduled_at',
        'duration_minutes',
        'status',
        'cancellation_reason',
        'meeting_link',
        'price',
        'is_paid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
        'price' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    /**
     * Get the mentor profile that owns the session.
     */
    public function mentorProfile(): BelongsTo
    {
        return $this->belongsTo(MentorProfile::class);
    }

    /**
     * Get the student that owns the session.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the review associated with the session.
     */
    public function review(): HasOne
    {
        return $this->hasOne(MentorshipReview::class);
    }

    /**
     * Scope a query to only include upcoming sessions.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', Carbon::now())
                     ->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include past sessions.
     */
    public function scopePast($query)
    {
        return $query->where(function ($query) {
            $query->where('scheduled_at', '<', Carbon::now())
                  ->orWhere('status', 'completed');
        });
    }

    /**
     * Scope a query to only include sessions with a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include sessions for a specific mentor.
     */
    public function scopeForMentor($query, $mentorProfileId)
    {
        return $query->where('mentor_profile_id', $mentorProfileId);
    }

    /**
     * Scope a query to only include sessions for a specific student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Check if the session can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        // Can only cancel confirmed sessions that haven't happened yet
        return $this->status === 'confirmed' && $this->scheduled_at > Carbon::now();
    }

    /**
     * Check if the session can be rescheduled.
     */
    public function canBeRescheduled(): bool
    {
        // Can only reschedule confirmed or pending sessions that haven't happened yet
        return in_array($this->status, ['confirmed', 'pending']) && $this->scheduled_at > Carbon::now();
    }

    /**
     * Check if the session is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the session is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if the session has a review.
     */
    public function hasReview(): bool
    {
        return $this->review()->exists();
    }

    /**
     * Format the scheduled date and time.
     */
    public function getFormattedScheduledAtAttribute(): string
    {
        return $this->scheduled_at->format('Y-m-d H:i');
    }

    /**
     * Get the end time of the session.
     */
    public function getEndTimeAttribute(): Carbon
    {
        return $this->scheduled_at->copy()->addMinutes($this->duration_minutes);
    }
}
