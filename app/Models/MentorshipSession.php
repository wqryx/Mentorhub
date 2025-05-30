<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipSession extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'course_id',
        'title',
        'description',
        'scheduled_at',
        'duration',
        'meeting_url',
        'status',
        'mentor_notes',
        'mentee_notes',
        'cancellation_reason',
        'proposed_time'
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'proposed_time' => 'datetime',
    ];

    /**
     * Obtiene el mentor asociado con la sesión.
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Obtiene el estudiante asociado con la sesión.
     */
    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    /**
     * Obtiene el curso asociado con la sesión.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Obtiene las reseñas asociadas con la sesión.
     */
    public function reviews()
    {
        return $this->hasMany(MentorshipReview::class, 'session_id');
    }

    /**
     * Scope para obtener sesiones programadas.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope para obtener sesiones completadas.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para obtener sesiones canceladas.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope para obtener sesiones pendientes.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para obtener sesiones futuras.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now());
    }

    /**
     * Scope para obtener sesiones pasadas.
     */
    public function scopePast($query)
    {
        return $query->where('scheduled_at', '<', now());
    }
}
