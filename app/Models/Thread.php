<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'forum_id',
        'user_id',
        'title',
        'content',
        'is_sticky',
        'is_locked',
        'last_post_at',
        'last_post_user_id',
        'view_count',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_sticky' => 'boolean',
        'is_locked' => 'boolean',
        'last_post_at' => 'datetime',
        'view_count' => 'integer',
    ];

    /**
     * Get the forum that owns the thread.
     */
    public function forum(): BelongsTo
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Get the user that owns the thread.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the last post user for the thread.
     */
    public function lastPostUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_post_user_id');
    }

    /**
     * Get the posts for the thread.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->orderBy('created_at');
    }

    /**
     * Get the post count for the thread.
     */
    public function getPostCountAttribute(): int
    {
        return $this->posts()->count();
    }

    /**
     * Get the reply count for the thread (total posts minus the original post).
     */
    public function getReplyCountAttribute(): int
    {
        return max(0, $this->posts()->count() - 1);
    }

    /**
     * Increment the view count for the thread.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Scope a query to only include sticky threads.
     */
    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    /**
     * Scope a query to only include locked threads.
     */
    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    /**
     * Scope a query to order threads by last post date.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('last_post_at', 'desc');
    }

    /**
     * Scope a query to order threads by creation date.
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order threads by popularity (view count).
     */
    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }
}