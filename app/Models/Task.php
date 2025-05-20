<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'module_id',
        'user_id'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDaysUntilDueAttribute()
    {
        return $this->due_date->diffInDays(now());
    }
}
