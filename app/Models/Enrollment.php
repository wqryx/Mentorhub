<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'academic_year',
        'status',
    ];

    /**
     * Get the user that owns the enrollment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the documents for the enrollment.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
