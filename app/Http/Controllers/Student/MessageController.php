<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Mostrar la bandeja de entrada de mensajes del estudiante
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Consulta base para mensajes
        $query = Message::where(function($q) use ($user) {
                $q->where('recipient_id', $user->id)
                  ->orWhere('sender_id', $user->id);
            });
        
        // Filtrar por tipo de mensaje
        if ($request->has('type')) {
            if ($request->type === 'inbox') {
                $query->where('recipient_id', $user->id);
            } elseif ($request->type === 'sent') {
                $query->where('sender_id', $user->id);
            } elseif ($request->type === 'unread') {
                $query->where('recipient_id', $user->id)
                      ->where('read', false);
            }
        } else {
            // Por defecto, mostrar la bandeja de entrada
            $query->where('recipient_id', $user->id);
        }
        
        // Filtrar por remitente
        if ($request->has('sender_id') && $request->sender_id) {
            $query->where('sender_id', $request->sender_id);
        }
        
        // Ordenar mensajes
        $query->orderBy('created_at', 'desc');
        
        $messages = $query->paginate(15);
        
        // Obtener contactos frecuentes (mentores y administradores)
        $mentors = User::role('mentor')->get();
        $admins = User::role('admin')->get();
        
        // Estadísticas para el panel lateral
        $stats = [
            'totalMessages' => Message::where(function($q) use ($user) {
                    $q->where('recipient_id', $user->id)
                      ->orWhere('sender_id', $user->id);
                })->count(),
            'unreadMessages' => Message::where('recipient_id', $user->id)
                ->where('read', false)
                ->count(),
            'sentMessages' => Message::where('sender_id', $user->id)->count()
        ];
        
        return view('student.messages.index', compact('messages', 'mentors', 'admins', 'stats'));
    }
    
    /**
     * Mostrar un mensaje específico
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $message = Message::where(function($q) use ($user) {
                $q->where('recipient_id', $user->id)
                  ->orWhere('sender_id', $user->id);
            })
            ->findOrFail($id);
        
        // Marcar como leído si el usuario es el destinatario
        if ($message->recipient_id === $user->id && !$message->read) {
            $message->read = true;
            $message->read_at = now();
            $message->save();
        }
        
        // Obtener conversación relacionada
        $conversation = Message::where(function($q) use ($user, $message) {
                $q->where(function($query) use ($user, $message) {
                    $query->where('sender_id', $user->id)
                          ->where('recipient_id', $message->sender_id === $user->id ? $message->recipient_id : $message->sender_id);
                })->orWhere(function($query) use ($user, $message) {
                    $query->where('recipient_id', $user->id)
                          ->where('sender_id', $message->sender_id === $user->id ? $message->recipient_id : $message->sender_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse();
        
        return view('student.messages.show', compact('message', 'conversation'));
    }
    
    /**
     * Mostrar el formulario para crear un nuevo mensaje
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        
        // Obtener posibles destinatarios (mentores y administradores)
        $mentors = User::role('mentor')->get();
        $admins = User::role('admin')->get();
        
        // Combinar destinatarios
        $recipients = $mentors->concat($admins);
        
        return view('student.messages.create', compact('recipients'));
    }
    
    /**
     * Almacenar un nuevo mensaje
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
        
        $user = Auth::user();
        
        $message = new Message();
        $message->sender_id = $user->id;
        $message->recipient_id = $validated['recipient_id'];
        $message->subject = $validated['subject'];
        $message->content = $validated['content'];
        $message->read = false;
        $message->save();
        
        return redirect()->route('student.messages.index')
            ->with('success', 'Mensaje enviado correctamente');
    }
    
    /**
     * Responder a un mensaje
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);
        
        $user = Auth::user();
        $originalMessage = Message::where(function($q) use ($user) {
                $q->where('recipient_id', $user->id)
                  ->orWhere('sender_id', $user->id);
            })
            ->findOrFail($id);
        
        $message = new Message();
        $message->sender_id = $user->id;
        $message->recipient_id = $originalMessage->sender_id === $user->id 
            ? $originalMessage->recipient_id 
            : $originalMessage->sender_id;
        $message->subject = 'RE: ' . $originalMessage->subject;
        $message->content = $validated['content'];
        $message->read = false;
        $message->parent_id = $originalMessage->id;
        $message->save();
        
        return redirect()->route('student.messages.show', $message->id)
            ->with('success', 'Respuesta enviada correctamente');
    }
    
    /**
     * Marcar un mensaje como leído o no leído
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleRead($id)
    {
        $user = Auth::user();
        $message = Message::where('recipient_id', $user->id)
            ->findOrFail($id);
        
        $message->read = !$message->read;
        $message->read_at = $message->read ? now() : null;
        $message->save();
        
        return response()->json([
            'success' => true,
            'read' => $message->read,
            'message' => $message->read ? 'Mensaje marcado como leído' : 'Mensaje marcado como no leído'
        ]);
    }
    
    /**
     * Eliminar un mensaje
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $message = Message::where(function($q) use ($user) {
                $q->where('recipient_id', $user->id)
                  ->orWhere('sender_id', $user->id);
            })
            ->findOrFail($id);
        
        // Si el usuario es el remitente, marcar como eliminado por el remitente
        if ($message->sender_id === $user->id) {
            $message->deleted_by_sender = true;
        }
        
        // Si el usuario es el destinatario, marcar como eliminado por el destinatario
        if ($message->recipient_id === $user->id) {
            $message->deleted_by_recipient = true;
        }
        
        // Si ambos usuarios han eliminado el mensaje, eliminarlo físicamente
        if ($message->deleted_by_sender && $message->deleted_by_recipient) {
            $message->delete();
        } else {
            $message->save();
        }
        
        return redirect()->route('student.messages.index')
            ->with('success', 'Mensaje eliminado correctamente');
    }
    
    /**
     * Marcar todos los mensajes como leídos
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Message::where('recipient_id', $user->id)
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now()
            ]);
        
        return redirect()->route('student.messages.index')
            ->with('success', 'Todos los mensajes han sido marcados como leídos');
    }
}
