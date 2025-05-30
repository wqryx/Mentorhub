<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'content',
        'read',
        'read_at',
        'parent_id',
        'deleted_by_sender',
        'deleted_by_recipient'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read' => 'boolean',
        'read_at' => 'datetime',
        'deleted_by_sender' => 'boolean',
        'deleted_by_recipient' => 'boolean',
    ];

    /**
     * Obtiene el remitente del mensaje.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Obtiene el destinatario del mensaje.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Obtiene el mensaje padre (en caso de ser una respuesta).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    /**
     * Obtiene las respuestas a este mensaje.
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    /**
     * Scope para filtrar mensajes no eliminados para un usuario específico.
     */
    public function scopeVisibleTo($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where(function ($subQ) use ($userId) {
                $subQ->where('sender_id', $userId)
                    ->where('deleted_by_sender', false);
            })->orWhere(function ($subQ) use ($userId) {
                $subQ->where('recipient_id', $userId)
                    ->where('deleted_by_recipient', false);
            });
        });
    }
}
