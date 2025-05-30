<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    /**
     * Nombre personalizado para los registros de actividad.
     *
     * @var string
     */
    protected static $activityLogName = 'Contenido';
    
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'type',
        'content',
        'order',
        'tutorial_id',
        'duration',
        'is_downloadable',
        'file_path',
        'file_type',
        'file_size',
        'external_url',
    ];
    
    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'is_downloadable' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    /**
     * Obtener el tutorial al que pertenece el contenido.
     */
    public function tutorial()
    {
        return $this->belongsTo(Tutorial::class);
    }
    
    /**
     * Scope para ordenar los contenidos por su orden.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
    
    /**
     * Scope para filtrar contenidos por tipo.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    /**
     * Determinar si el contenido es de tipo texto.
     */
    public function isText()
    {
        return $this->type === 'text';
    }
    
    /**
     * Determinar si el contenido es de tipo video.
     */
    public function isVideo()
    {
        return $this->type === 'video';
    }
    
    /**
     * Determinar si el contenido es de tipo archivo.
     */
    public function isFile()
    {
        return $this->type === 'file';
    }
    
    /**
     * Determinar si el contenido es de tipo código.
     */
    public function isCode()
    {
        return $this->type === 'code';
    }
    
    /**
     * Determinar si el contenido es de tipo quiz.
     */
    public function isQuiz()
    {
        return $this->type === 'quiz';
    }
    
    /**
     * Determinar si el contenido es de tipo iframe.
     */
    public function isIframe()
    {
        return $this->type === 'iframe';
    }
    
    /**
     * Obtener la URL completa del archivo si existe.
     */
    public function getFileUrl()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        
        return null;
    }
}
