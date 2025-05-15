<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class MentorSpecialty extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (MentorSpecialty $specialty) {
            $specialty->slug = $specialty->slug ?? Str::slug($specialty->name);
        });

        static::updating(function (MentorSpecialty $specialty) {
            if ($specialty->isDirty('name') && !$specialty->isDirty('slug')) {
                $specialty->slug = Str::slug($specialty->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the mentor profiles that belong to the specialty.
     */
    public function mentorProfiles(): BelongsToMany
    {
        return $this->belongsToMany(MentorProfile::class, 'mentor_specialty')
                    ->withPivot('years_experience')
                    ->withTimestamps();
    }

    /**
     * Scope a query to search specialties by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                     ->orWhere('description', 'like', "%{$search}%");
    }
}
