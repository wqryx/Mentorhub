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
        'title',
        'description',
        'type',
        'url',
        'file_path',
        'creator_id',
        'course_id',
        'is_public',
        'is_premium',
        'tags',
        'views_count',
        'downloads_count',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'is_public' => 'boolean',
        'is_premium' => 'boolean',
        'tags' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Obtiene el creador del recurso.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Obtiene el curso asociado con el recurso.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
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
