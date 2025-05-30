<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutorial extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    /**
     * Nombre personalizado para los registros de actividad.
     *
     * @var string
     */
    protected static $activityLogName = 'Tutorial';
    
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'order',
        'module_id',
        'status',
        'duration',
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
     * Obtener el módulo al que pertenece el tutorial.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    
    /**
     * Obtener los contenidos asociados al tutorial.
     */
    public function contents()
    {
        return $this->hasMany(Content::class)->orderBy('order');
    }
    
    /**
     * Obtener los usuarios que han completado este tutorial.
     */
    public function completedByUsers()
    {
        return $this->belongsToMany(User::class, 'tutorial_user', 'tutorial_id', 'user_id')
            ->withPivot('completed_at')
            ->withTimestamps();
    }
    
    /**
     * Verificar si un usuario ha completado este tutorial.
     */
    public function isCompletedByUser($userId)
    {
        return $this->completedByUsers()->where('user_id', $userId)->exists();
    }
    
    /**
     * Scope para ordenar los tutoriales por su orden.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
    
    /**
     * Scope para filtrar tutoriales activos.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    /**
     * Scope para filtrar tutoriales gratuitos.
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }
    
    /**
     * Obtener el curso al que pertenece este tutorial (a través del módulo).
     */
    public function course()
    {
        return $this->module->course;
    }
}
