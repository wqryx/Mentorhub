<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorshipReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'mentorship_session_id',
        'student_id',
        'mentor_profile_id',
        'rating',
        'review',
        'is_anonymous',
        'is_approved',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'is_anonymous' => 'boolean',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the session that owns the review.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(MentorshipSession::class, 'mentorship_session_id');
    }

    /**
     * Get the student that owns the review.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the mentor profile that the review is for.
     */
    public function mentorProfile(): BelongsTo
    {
        return $this->belongsTo(MentorProfile::class);
    }

    /**
     * Scope a query to only include approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope a query to only include reviews with a minimum rating.
     */
    public function scopeMinimumRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }

    /**
     * Scope a query to only include reviews for a specific mentor.
     */
    public function scopeForMentor($query, $mentorProfileId)
    {
        return $query->where('mentor_profile_id', $mentorProfileId);
    }

    /**
     * Scope a query to only include reviews by a specific student.
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Get the student name for display, respecting anonymity.
     */
    public function getDisplayStudentNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous Student';
        }
        
        return $this->student ? $this->student->name : 'Unknown Student';
    }

    /**
     * Get the truncated review text for previews.
     */
    public function getTruncatedReviewAttribute(): string
    {
        if (strlen($this->review) <= 100) {
            return $this->review;
        }
        
        return substr($this->review, 0, 100) . '...';
    }
}
