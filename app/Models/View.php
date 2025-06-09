<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class View extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'viewable_id',
        'viewable_type',
        'viewed_at',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Get the parent viewable model (Module, Tutorial, etc.).
     */
    public function viewable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that viewed the resource.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include views from a specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Record a view for the given model.
     */
    public static function recordView($model, $userId = null)
    {
        if (auth()->check()) {
            $userId = $userId ?? auth()->id();
            
            return static::create([
                'user_id' => $userId,
                'viewable_id' => $model->id,
                'viewable_type' => get_class($model),
                'viewed_at' => now(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        }
        
        return null;
    }
}
