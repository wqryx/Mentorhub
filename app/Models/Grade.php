<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'grade',
        'module_id',
        'user_id',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
