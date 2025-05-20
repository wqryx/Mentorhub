<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
