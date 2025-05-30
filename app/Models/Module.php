<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    /**
     * Nombre personalizado para los registros de actividad.
     *
     * @var string
     */
    protected static $activityLogName = 'Módulo';
    
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'order',
        'course_id',
        'status',
        'is_free',
    ];
    
    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'is_free' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    /**
     * Obtener el curso al que pertenece el módulo.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Obtener los tutoriales asociados al módulo.
     */
    public function tutorials()
    {
        return $this->hasMany(Tutorial::class)->orderBy('order');
    }
    
    /**
     * Scope para ordenar los módulos por su orden.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
    
    /**
     * Scope para filtrar módulos activos.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    /**
     * Scope para filtrar módulos gratuitos.
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }
    
    /**
     * Obtener el progreso del módulo para un usuario específico.
     */
    public function getProgressForUser($userId)
    {
        $tutorials = $this->tutorials;
        
        if ($tutorials->isEmpty()) {
            return 0;
        }
        
        $completedCount = 0;
        
        foreach ($tutorials as $tutorial) {
            $isCompleted = $tutorial->isCompletedByUser($userId);
            if ($isCompleted) {
                $completedCount++;
            }
        }
        
        return ($completedCount / $tutorials->count()) * 100;
    }
}
