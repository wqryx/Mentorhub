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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mentor_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'mentor_id',
        'student_id',
        'course_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'duration_minutes',
        'status', // scheduled, completed, cancelled
        'type',   // one_time, recurring
        'format', // video_call, phone_call, in_person
        'meeting_link',
        'student_goals',
        'mentor_notes',
        'outcome_summary',
        'cancellation_reason',
        'is_recurring',
        'recurrence_pattern',
        'session_fee',
        'is_paid',
        'completed_at',
        'cancelled_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'duration_minutes' => 'integer',
        'session_fee' => 'decimal:2',
        'is_paid' => 'boolean',
        'is_recurring' => 'boolean',
        'student_goals' => 'array',
    ];

    /**
     * Get the mentor that owns the session.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Get the student that owns the session.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Alias for student (for backward compatibility).
     */
    public function mentee(): BelongsTo
    {
        return $this->student();
    }

    /**
     * Get the review associated with the session.
     */
    public function review(): HasOne
    {
        return $this->hasOne(MentorshipReview::class);
    }
    
    /**
     * Get the course associated with the session.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Get the mentor's profile for this session.
     */
    public function mentorProfile()
    {
        return $this->hasOneThrough(
            UserProfile::class,
            User::class,
            'id',
            'user_id',
            'mentor_id',
            'id'
        );
    }
    
    /**
     * Get the student's profile for this session.
     */
    public function studentProfile()
    {
        return $this->hasOneThrough(
            UserProfile::class,
            User::class,
            'id',
            'user_id',
            'student_id',
            'id'
        );
    }

    /**
     * Scope a query to only include upcoming sessions.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', Carbon::now())
                     ->where('status', 'scheduled')
                     ->orderBy('start_time');
    }

    /**
     * Scope a query to only include past sessions.
     */
    public function scopePast($query)
    {
        return $query->where(function ($query) {
            $query->where('start_time', '<', Carbon::now())
                  ->orWhereIn('status', ['completed', 'cancelled']);
        })->orderBy('start_time', 'desc');
    }
    
    /**
     * Scope a query to only include sessions for a specific student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
    
    /**
     * Scope a query to only include sessions for a specific mentor.
     */
    public function scopeForMentor($query, $mentorId)
    {
        return $query->where('mentor_id', $mentorId);
    }
    
    /**
     * Scope a query to only include sessions with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Check if the session is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->start_time->isFuture() && $this->status === 'scheduled';
    }
    
    /**
     * Check if the session is in progress.
     */
    public function isInProgress(): bool
    {
        $now = now();
        return $this->start_time->lt($now) && 
               $this->end_time->gt($now) && 
               $this->status === 'scheduled';
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
     * Get the duration of the session in a human-readable format.
     */
    public function getDurationForHumansAttribute(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return $minutes > 0 
                ? "{$hours}h {$minutes}m" 
                : "{$hours} " . ($hours === 1 ? 'hora' : 'horas');
        }
        
        return "{$minutes} minutos";
    }

    /**
     * Check if the session can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        // Can only cancel scheduled sessions that haven't started yet
        return $this->status === 'scheduled' && $this->start_time > now();
    }

    /**
     * Check if the session can be rescheduled.
     */
    public function canBeRescheduled(): bool
    {
        // Can only reschedule scheduled sessions that haven't started yet
        return $this->status === 'scheduled' && $this->start_time > now();
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
    public function getFormattedStartTimeAttribute(): string
    {
        return $this->start_time->format('Y-m-d H:i');
    }

    /**
     * Get the end time of the session.
     */
    public function getEndTimeAttribute(): Carbon
    {
        return $this->start_time->copy()->addMinutes($this->duration_minutes);
    }

    /**
     * Get the status as a human-readable label with appropriate styling.
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'scheduled' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
        ];
        
        $status = $this->status;
        $class = $statuses[$status] ?? 'bg-gray-100 text-gray-800';
        
        return sprintf(
            '<span class="px-2 py-1 text-xs font-medium rounded-full %s">%s</span>',
            $class,
            ucfirst($status)
        );
    }
}
