<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class MentorProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'bio',
        'expertise',
        'experience',
        'linkedin_url',
        'github_url',
        'website',
        'hourly_rate',
        'is_available',
        'is_verified',
        'rating',
        'sessions_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
        'rating' => 'integer',
        'sessions_completed' => 'integer',
    ];

    /**
     * Get the user that owns the mentor profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialties that belong to the mentor.
     */
    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(MentorSpecialty::class, 'mentor_specialty')
                    ->withPivot('years_experience')
                    ->withTimestamps();
    }

    /**
     * Get the availability slots for the mentor.
     */
    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(MentorAvailability::class);
    }

    /**
     * Get the mentorship sessions for the mentor.
     */
    public function mentorshipSessions(): HasMany
    {
        return $this->hasMany(MentorshipSession::class);
    }

    /**
     * Get the reviews for the mentor.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(MentorshipReview::class);
    }

    /**
     * Get the students mentored by this mentor.
     */
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, MentorshipSession::class, 'mentor_profile_id', 'id', 'id', 'student_id');
    }

    /**
     * Get the average rating of the mentor.
     */
    public function getAverageRatingAttribute(): float
    {
        if ($this->reviews->isEmpty()) {
            return 0.0;
        }
        
        return $this->reviews->avg('rating');
    }

    /**
     * Scope a query to only include verified mentors.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to only include available mentors.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to filter mentors by specialty.
     */
    public function scopeWithSpecialty($query, $specialtyId)
    {
        return $query->whereHas('specialties', function ($query) use ($specialtyId) {
            $query->where('mentor_specialty_id', $specialtyId);
        });
    }

    /**
     * Scope a query to filter mentors by hourly rate range.
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('hourly_rate', [$min, $max]);
    }

    /**
     * Scope a query to filter mentors by minimum rating.
     */
    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }
}
