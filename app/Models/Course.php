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
        'title',
        'description',
        'image',
        'status',
        'price',
        'duration',
        'level',
        'category_id',
        'instructor_id',
        'is_featured',
        'is_premium',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];
    
    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_premium' => 'boolean',
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
     * Obtener el instructor asociado al curso.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
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