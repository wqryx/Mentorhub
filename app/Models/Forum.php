<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forum extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'category',
        'is_active',
        'created_by',
        'slug',
        'icon',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the threads for the forum.
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Get the user who created the forum.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the latest threads for the forum.
     */
    public function latestThreads(int $limit = 5): HasMany
    {
        return $this->threads()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit);
    }

    /**
     * Get the thread count for the forum.
     */
    public function getThreadCountAttribute(): int
    {
        return $this->threads()->count();
    }

    /**
     * Get the post count for the forum.
     */
    public function getPostCountAttribute(): int
    {
        return $this->threads()->withCount('posts')->get()->sum('posts_count');
    }

    /**
     * Scope a query to only include active forums.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include forums in a specific category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}