<?php

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The chat message instance.
     *
     * @var \App\Models\ChatMessage
     */
    public $message;

    /**
     * The user who sent the message.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * The chat room where the message was sent.
     *
     * @var \App\Models\ChatRoom
     */
    public $chatRoom;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message, User $user, ChatRoom $chatRoom)
    {
        $this->message = $message;
        $this->user = $user;
        $this->chatRoom = $chatRoom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->chatRoom->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'content' => $this->message->content,
                'created_at' => $this->message->created_at,
                'attachment' => $this->message->attachment,
                'attachment_type' => $this->message->attachment_type,
                'is_system_message' => $this->message->is_system_message,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'profile_photo' => $this->user->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&color=7F9CF5&background=EBF4FF',
            ],
            'chat_room_id' => $this->chatRoom->id,
        ];
    }
}
