<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Speciality extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    /**
     * Scope a query to only include active specialities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Get the courses associated with the speciality.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    
    /**
     * Get the users (mentors) associated with this speciality.
     */
    public function mentors()
    {
        return $this->belongsToMany(User::class, 'mentor_speciality', 'speciality_id', 'user_id')
            ->withTimestamps();
    }
    
    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($speciality) {
            if (empty($speciality->slug)) {
                $speciality->slug = \Illuminate\Support\Str::slug($speciality->name);
            }
        });
        
        static::updating(function ($speciality) {
            if ($speciality->isDirty('name') && empty($speciality->slug)) {
                $speciality->slug = \Illuminate\Support\Str::slug($speciality->name);
            }
        });
    }
}
