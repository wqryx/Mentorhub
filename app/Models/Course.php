<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    /**
     * Nombre personalizado para los registros de actividad.
     *
     * @var string
     */
    protected static $activityLogName = 'Curso';
    
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // Renamed from 'title'
        'slug',
        'code', // New
        'description',
        'level',
        'credits', // New
        'hours_per_week', // New
        'start_date', // New
        'end_date', // New
        'classroom', // New
        'schedule', // New
        'image_path', // Renamed from 'image'
        'is_active', // New, effectively replaces 'status' or 'is_published' logic from controller
        'creator_id', // Replaces 'instructor_id'
        'speciality_id', // Added for speciality relationship
    ];
    
    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    /**
     * Obtener la categoría asociada al curso.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Obtener la especialidad asociada al curso.
     */
    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }
    
    /**
     * Obtener el creador (mentor) asociado al curso.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    
    /**
     * Alias para compatibilidad con código existente que usa instructor
     * @deprecated Use creator() instead
     */
    public function instructor()
    {
        return $this->creator();
    }
    
    /**
     * Obtener los módulos asociados al curso.
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    
    /**
     * Obtener los estudiantes inscritos en el curso.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
            ->withPivot('progress', 'completed', 'enrolled_at', 'completed_at')
            ->withTimestamps();
    }
    
    /**
     * Obtener las reseñas del curso.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Obtener las inscripciones al curso.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    
    /**
     * Obtener el rating promedio del curso.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }
    
    /**
     * Scope para filtrar cursos destacados.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Scope para filtrar cursos premium.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }
    
    /**
     * Scope para filtrar cursos publicados.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}