<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'url',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'is_active',
        'order',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Obtiene el curso al que pertenece el recurso.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Obtiene el creador del recurso a través del curso.
     */
    public function creator()
    {
        return $this->hasOneThrough(
            User::class,
            Course::class,
            'id', // Foreign key on courses table
            'id', // Foreign key on users table
            'course_id', // Local key on resources table
            'creator_id' // Local key on courses table
        );
    }

    /**
     * Obtiene los comentarios asociados con el recurso.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Scope para filtrar recursos públicos.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para filtrar recursos premium.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope para filtrar recursos por tipo.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para filtrar recursos por etiqueta.
     */
    public function scopeWithTag($query, $tag)
    {
        return $query->where('tags', 'like', '%"' . $tag . '"%');
    }

    /**
     * Incrementa el contador de vistas.
     */
    public function incrementViewsCount()
    {
        $this->increment('views_count');
        return $this;
    }

    /**
     * Incrementa el contador de descargas.
     */
    public function incrementDownloadsCount()
    {
        $this->increment('downloads_count');
        return $this;
    }
}
