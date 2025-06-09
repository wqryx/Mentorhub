<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Invitation extends Model
{
    protected $fillable = [
        'email',
        'mentor_id',
        'token',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
        'created_at',
        'updated_at',
    ];

    public static function createInvitation($email, $mentorId)
    {
        return self::create([
            'email' => $email,
            'mentor_id' => $mentorId,
            'token' => Str::random(32),
            'expires_at' => now()->addDays(7),
        ]);
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
