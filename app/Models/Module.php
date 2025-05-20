<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'credits',
        'semester',
        'progress',
        'pending_tasks'
    ];

    public function students()
    {
        return $this->belongsToMany(User::class, 'module_user', 'module_id', 'user_id')
            ->withPivot('enrollment_date')
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function resources()
    {
        return $this->belongsToMany(Resource::class, 'module_resource', 'module_id', 'resource_id')
            ->withTimestamps();
    }
}
