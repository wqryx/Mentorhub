<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'image',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the messages for the chat room.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the users that belong to the chat room.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_room_user')
                    ->withPivot(['role', 'is_muted', 'last_read_at'])
                    ->withTimestamps();
    }

    /**
     * Check if the chat room is private.
     */
    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    /**
     * Check if the chat room is a group.
     */
    public function isGroup(): bool
    {
        return $this->type === 'group';
    }

    /**
     * Get the chat room name or generate one based on participants for private chats.
     */
    public function getDisplayName(User $currentUser = null): string
    {
        if ($this->isGroup() || !$currentUser) {
            return $this->name ?? 'Chat Room ' . $this->id;
        }
        
        // For private chats, display the other participant's name
        $otherUser = $this->users->where('id', '!=', $currentUser->id)->first();
        return $otherUser ? $otherUser->name : 'Chat Room ' . $this->id;
    }

    /**
     * Get the chat room avatar image or generate one based on participants for private chats.
     */
    public function getAvatarImage(User $currentUser = null): string
    {
        if ($this->image) {
            return $this->image;
        }
        
        if ($this->isPrivate() && $currentUser) {
            $otherUser = $this->users->where('id', '!=', $currentUser->id)->first();
            return $otherUser && $otherUser->profile_photo 
                ? $otherUser->profile_photo 
                : 'https://ui-avatars.com/api/?name=' . urlencode($this->getDisplayName($currentUser)) . '&color=7F9CF5&background=EBF4FF';
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'Group Chat') . '&color=7F9CF5&background=EBF4FF';
    }
}
