<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;
    
    /**
     * Nombre personalizado para los registros de actividad.
     *
     * @var string
     */
    protected static $activityLogName = 'Usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, mentor, student
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a mentor
     */
    public function isMentor()
    {
        return $this->role === 'mentor';
    }

    /**
     * Check if user is a student
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }
    
    /**
     * Obtiene las sesiones donde el usuario es mentor.
     */
    public function mentorSessions()
    {
        return $this->hasMany(MentorshipSession::class, 'mentor_id');
    }

    /**
     * Obtiene las sesiones donde el usuario es estudiante.
     */
    public function menteeSessions()
    {
        return $this->hasMany(MentorshipSession::class, 'mentee_id');
    }

    /**
     * Obtiene las solicitudes de mentoría pendientes para el mentor.
     */
    public function mentorRequests()
    {
        return $this->mentorSessions()->where('status', 'pending');
    }

    /**
     * Obtiene los estudiantes asociados con el mentor a través de sesiones.
     */
    public function menteeStudents()
    {
        return $this->hasManyThrough(
            User::class,
            MentorshipSession::class,
            'mentor_id',
            'id',
            'id',
            'mentee_id'
        )->distinct();
    }

    /**
     * Obtiene los mentores asociados con el estudiante a través de sesiones.
     */
    public function mentors()
    {
        return $this->hasManyThrough(
            User::class,
            MentorshipSession::class,
            'mentee_id',
            'id',
            'id',
            'mentor_id'
        )->distinct();
    }

    /**
     * Obtiene las reseñas recibidas por el usuario.
     */
    public function receivedReviews()
    {
        return $this->hasMany(MentorshipReview::class, 'target_id');
    }

    /**
     * Obtiene las reseñas escritas por el usuario.
     */
    public function givenReviews()
    {
        return $this->hasMany(MentorshipReview::class, 'author_id');
    }

    /**
     * Obtiene la calificación promedio del usuario como mentor.
     */
    public function getAverageMentorRatingAttribute()
    {
        $reviews = $this->receivedReviews()->where('is_mentor_review', false)->get();
        if ($reviews->isEmpty()) {
            return 0;
        }
        return $reviews->avg('rating');
    }

    /**
     * Obtiene la calificación promedio del usuario como estudiante.
     */
    public function getAverageStudentRatingAttribute()
    {
        $reviews = $this->receivedReviews()->where('is_mentor_review', true)->get();
        if ($reviews->isEmpty()) {
            return 0;
        }
        return $reviews->avg('rating');
    }

    /**
     * Obtiene la URL de la foto de perfil del usuario.
     */
    public function getProfilePhotoUrlAttribute()
    {
        // Si el usuario tiene una foto de perfil, devolver la URL
        if (isset($this->profile) && $this->profile->photo_path) {
            return asset('storage/' . $this->profile->photo_path);
        }
        
        // Si no, devolver una imagen por defecto
        return asset('img/default-avatar.png');
    }
    
    /**
     * Obtiene los mensajes enviados por el usuario.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    
    /**
     * Obtiene los mensajes recibidos por el usuario.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }
    
    /**
     * Obtiene los mensajes no leídos del usuario.
     */
    public function unreadMessages()
    {
        return $this->receivedMessages()
            ->where('read', false)
            ->where('deleted_by_recipient', false);
    }
}
