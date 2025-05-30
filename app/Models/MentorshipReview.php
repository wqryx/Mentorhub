<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipReview extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'author_id',
        'target_id',
        'rating',
        'comment',
        'is_mentor_review'
    ];

    /**
     * Obtiene la sesión asociada con la reseña.
     */
    public function session()
    {
        return $this->belongsTo(MentorshipSession::class);
    }

    /**
     * Obtiene el autor de la reseña.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Obtiene el usuario objetivo de la reseña.
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}
