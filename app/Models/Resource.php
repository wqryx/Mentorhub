<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'name',
        'type',
        'url',
        'description',
        'resourceable_type',
        'resourceable_id'
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_resource', 'resource_id', 'module_id')
            ->withTimestamps();
    }

    public function resourceable()
    {
        return $this->morphTo();
    }
}
