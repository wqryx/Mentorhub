<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'chat_room_id',
        'user_id',
        'content',
        'attachment',
        'attachment_type',
        'is_system_message',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_system_message' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the chat room that the message belongs to.
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Get the user that sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reactions to this message.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(ChatMessageReaction::class);
    }

    /**
     * Scope a query to only include messages for a specific chat room.
     */
    public function scopeChatRoom($query, $chatRoomId)
    {
        return $query->where('chat_room_id', $chatRoomId);
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Check if the message has been read.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Check if the message is a system message.
     */
    public function isSystemMessage(): bool
    {
        return $this->is_system_message;
    }

    /**
     * Check if the message has an attachment.
     */
    public function hasAttachment(): bool
    {
        return $this->attachment !== null;
    }
}
