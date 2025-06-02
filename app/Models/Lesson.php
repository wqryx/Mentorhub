<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'module_id',
        'tutorial_id',
        'title',
        'description',
        'content',
        'duration_minutes',
        'order',
        'is_premium',
        'status',
        'created_by',
        'video_url',
        'resources',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration_minutes' => 'integer',
        'order' => 'integer',
        'is_premium' => 'boolean',
        'resources' => 'array',
    ];

    /**
     * Get the module that owns the lesson.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the tutorial that owns the lesson.
     */
    public function tutorial(): BelongsTo
    {
        return $this->belongsTo(Tutorial::class);
    }

    /**
     * Get the user who created the lesson.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the contents for the lesson.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class)->orderBy('order');
    }

    /**
     * Get the students who have completed this lesson.
     */
    public function completedByStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_completions')
                    ->withTimestamps();
    }

    /**
     * Get the course through the module.
     */
    public function course()
    {
        return $this->module ? $this->module->course : null;
    }

    /**
     * Get the next lesson in the module.
     */
    public function getNextLessonAttribute()
    {
        return Lesson::where('module_id', $this->module_id)
                    ->where('order', '>', $this->order)
                    ->orderBy('order')
                    ->first();
    }

    /**
     * Get the previous lesson in the module.
     */
    public function getPreviousLessonAttribute()
    {
        return Lesson::where('module_id', $this->module_id)
                    ->where('order', '<', $this->order)
                    ->orderBy('order', 'desc')
                    ->first();
    }

    /**
     * Check if the lesson has been completed by a specific user.
     */
    public function isCompletedBy(User $user): bool
    {
        return $this->completedByStudents()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope a query to only include premium lessons.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope a query to only include free lessons.
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Scope a query to only include published lessons.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to order lessons by their order within a module.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}