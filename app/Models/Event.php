<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'type',
        'module_id'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the user that owns the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
