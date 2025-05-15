<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a list of the user's chat rooms.
     */
    public function index()
    {
        $user = Auth::user();
        $chatRooms = $user->chatRooms();
        
        return view('chat.index', [
            'chatRooms' => $chatRooms->orderBy('updated_at', 'desc')->get(),
            'user' => $user,
        ]);
    }

    /**
     * Display a specific chat room with messages.
     */
    public function show(ChatRoom $chatRoom)
    {
        $user = Auth::user();
        
        // Check if the user is a member of this chat room
        if (!$chatRoom->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'You do not have access to this chat room.');
        }
        
        // Mark messages as read when opening the chat
        $chatRoom->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        // Update last read time for the user in this chat room
        $chatRoom->users()->updateExistingPivot($user->id, ['last_read_at' => now()]);
        
        // Get messages with pagination
        $messages = $chatRoom->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('chat.show', [
            'chatRoom' => $chatRoom,
            'messages' => $messages->reverse(),
            'user' => $user,
        ]);
    }

    /**
     * Create a new private chat room with another user.
     */
    public function createPrivateChat(User $otherUser)
    {
        $user = Auth::user();
        
        // Check if a private chat already exists between these users
        $existingChatRoom = $user->chatRooms()
            ->where('type', 'private')
            ->whereHas('users', function ($query) use ($otherUser) {
                $query->where('users.id', $otherUser->id);
            })
            ->first();
        
        if ($existingChatRoom) {
            return redirect()->route('chat.show', $existingChatRoom);
        }
        
        // Create a new private chat room
        $chatRoom = ChatRoom::create([
            'type' => 'private',
            'is_active' => true,
        ]);
        
        // Add both users to the chat room
        $chatRoom->users()->attach([
            $user->id => ['role' => 'owner'],
            $otherUser->id => ['role' => 'member'],
        ]);
        
        // Create a system message indicating the chat creation
        ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $user->id,
            'content' => 'Chat started',
            'is_system_message' => true,
        ]);
        
        return redirect()->route('chat.show', $chatRoom);
    }

    /**
     * Create a new group chat room.
     */
    public function createGroupChat(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
            'image' => 'nullable|image|max:1024',
        ]);
        
        // Create a new group chat room
        $chatRoom = ChatRoom::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => 'group',
            'is_active' => true,
        ]);
        
        // Upload image if provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat-rooms', 'public');
            $chatRoom->update(['image' => Storage::url($path)]);
        }
        
        // Add the creator as owner
        $chatRoom->users()->attach($user->id, ['role' => 'owner']);
        
        // Add other users as members
        foreach ($validated['users'] as $userId) {
            if ($userId != $user->id) {
                $chatRoom->users()->attach($userId, ['role' => 'member']);
            }
        }
        
        // Create a system message indicating the group creation
        ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $user->id,
            'content' => $user->name . ' created this group',
            'is_system_message' => true,
        ]);
        
        return redirect()->route('chat.show', $chatRoom);
    }

    /**
     * Send a new message in a chat room.
     */
    public function sendMessage(Request $request, ChatRoom $chatRoom)
    {
        $user = Auth::user();
        
        // Check if the user is a member of this chat room
        if (!$chatRoom->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'You do not have access to this chat room.');
        }
        
        // Check if the user is muted in this chat room
        if ($chatRoom->users()->where('user_id', $user->id)->first()->pivot->is_muted) {
            abort(403, 'You are muted in this chat room.');
        }
        
        $validated = $request->validate([
            'content' => 'required_without:attachment|string|max:10000',
            'attachment' => 'nullable|file|max:10240',
        ]);
        
        $messageData = [
            'chat_room_id' => $chatRoom->id,
            'user_id' => $user->id,
            'content' => $validated['content'] ?? '',
            'is_system_message' => false,
        ];
        
        // Upload attachment if provided
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat-attachments', 'public');
            $messageData['attachment'] = Storage::url($path);
            $messageData['attachment_type'] = $file->getMimeType();
        }
        
        // Create the message
        $message = ChatMessage::create($messageData);
        
        // Update the chat room's updated_at timestamp
        $chatRoom->touch();
        
        // Broadcast the message using the event
        broadcast(new MessageSent($message, $user, $chatRoom))->toOthers();
        
        // Return the message if it's an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message->load('user'),
            ]);
        }
        
        return back();
    }

    /**
     * Mark all messages in a chat room as read.
     */
    public function markAsRead(ChatRoom $chatRoom)
    {
        $user = Auth::user();
        
        // Check if the user is a member of this chat room
        if (!$chatRoom->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'You do not have access to this chat room.');
        }
        
        // Mark all unread messages as read
        $chatRoom->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        // Update last read time for the user in this chat room
        $chatRoom->users()->updateExistingPivot($user->id, ['last_read_at' => now()]);
        
        return response()->json(['status' => 'success']);
    }

    /**
     * Get more messages for a chat room (pagination).
     */
    public function getMoreMessages(Request $request, ChatRoom $chatRoom)
    {
        $user = Auth::user();
        
        // Check if the user is a member of this chat room
        if (!$chatRoom->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'You do not have access to this chat room.');
        }
        
        $lastMessageId = $request->input('last_message_id');
        $messages = $chatRoom->messages()
            ->with('user')
            ->where('id', '<', $lastMessageId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->reverse();
        
        return response()->json([
            'messages' => $messages,
            'has_more' => $messages->count() == 20,
        ]);
    }
}
