<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageAttachment;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        $this->middleware('auth');
        // El middleware de roles ha sido eliminado temporalmente
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::with(['sender', 'recipient'])
            ->where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $users = User::orderBy('name')->get();
        $roles = Role::all();
        
        return view('admin.messages.index', compact('messages', 'users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $roles = Role::all();
        
        return view('admin.messages.create', compact('users', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:user,role,all',
            'user_id' => 'required_if:recipient_type,user|exists:users,id',
            'role_id' => 'required_if:recipient_type,role|exists:roles,id',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max por archivo
        ]);
        
        $message = new Message([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'sender_id' => Auth::id(),
        ]);
        
        // Asignar destinatario según el tipo seleccionado
        if ($validated['recipient_type'] === 'user') {
            $message->recipient_id = $validated['user_id'];
        }
        
        $message->save();
        
        // Si es un mensaje para un rol o todos, crear copias individuales
        if ($validated['recipient_type'] === 'role') {
            $role = Role::findById($validated['role_id']);
            $users = User::role($role->name)->get();
            
            foreach ($users as $user) {
                if ($user->id != Auth::id()) { // No enviar a uno mismo
                    Message::create([
                        'subject' => $validated['subject'],
                        'content' => $validated['content'],
                        'sender_id' => Auth::id(),
                        'recipient_id' => $user->id,
                        'parent_id' => $message->id,
                    ]);
                }
            }
        } elseif ($validated['recipient_type'] === 'all') {
            $users = User::all();
            
            foreach ($users as $user) {
                if ($user->id != Auth::id()) { // No enviar a uno mismo
                    Message::create([
                        'subject' => $validated['subject'],
                        'content' => $validated['content'],
                        'sender_id' => Auth::id(),
                        'recipient_id' => $user->id,
                        'parent_id' => $message->id,
                    ]);
                }
            }
        }
        
        // Procesar archivos adjuntos si existen
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message_attachments', 'public');
                
                MessageAttachment::create([
                    'message_id' => $message->id,
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $path,
                    'filesize' => $file->getSize(),
                    'filetype' => $file->getMimeType(),
                ]);
            }
        }
        
        return redirect()->route('admin.messages.index')
            ->with('success', 'Mensaje enviado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $message = Message::with(['sender', 'recipient', 'attachments'])->findOrFail($id);
        
        // Marcar como leído si el usuario actual es el destinatario
        if ($message->recipient_id == Auth::id() && !$message->read_at) {
            $message->read_at = now();
            $message->save();
        }
        
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $message = Message::with(['sender', 'recipient', 'attachments'])->findOrFail($id);
        
        // Solo el remitente puede editar el mensaje
        if ($message->sender_id != Auth::id()) {
            return redirect()->route('admin.messages.index')
                ->with('error', 'No tienes permiso para editar este mensaje');
        }
        
        return view('admin.messages.edit', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $message = Message::findOrFail($id);
        
        // Solo el remitente puede actualizar el mensaje
        if ($message->sender_id != Auth::id()) {
            return redirect()->route('admin.messages.index')
                ->with('error', 'No tienes permiso para actualizar este mensaje');
        }
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max por archivo
        ]);
        
        $message->update([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
        ]);
        
        // Procesar archivos adjuntos si existen
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message_attachments', 'public');
                
                MessageAttachment::create([
                    'message_id' => $message->id,
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $path,
                    'filesize' => $file->getSize(),
                    'filetype' => $file->getMimeType(),
                ]);
            }
        }
        
        return redirect()->route('admin.messages.show', $message->id)
            ->with('success', 'Mensaje actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = Message::findOrFail($id);
        
        // Verificar permisos (solo el remitente o destinatario pueden eliminar)
        if ($message->sender_id != Auth::id() && $message->recipient_id != Auth::id()) {
            return redirect()->route('admin.messages.index')
                ->with('error', 'No tienes permiso para eliminar este mensaje');
        }
        
        // Eliminar archivos adjuntos
        foreach ($message->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->filepath);
            $attachment->delete();
        }
        
        $message->delete();
        
        return redirect()->route('admin.messages.index')
            ->with('success', 'Mensaje eliminado exitosamente');
    }
    
    /**
     * Marcar un mensaje como leído
     */
    public function markAsRead(string $id)
    {
        $message = Message::findOrFail($id);
        
        if ($message->recipient_id == Auth::id()) {
            $message->read_at = now();
            $message->save();
            
            return redirect()->back()->with('success', 'Mensaje marcado como leído');
        }
        
        return redirect()->back()->with('error', 'No puedes marcar este mensaje como leído');
    }
    
    /**
     * Responder a un mensaje
     */
    public function reply(string $id)
    {
        $originalMessage = Message::with(['sender', 'recipient'])->findOrFail($id);
        
        // Solo el destinatario puede responder
        if ($originalMessage->recipient_id != Auth::id()) {
            return redirect()->route('admin.messages.index')
                ->with('error', 'No puedes responder a este mensaje');
        }
        
        return view('admin.messages.create', [
            'replyTo' => $originalMessage,
            'users' => User::orderBy('name')->get(),
            'roles' => Role::all(),
        ]);
    }
    
    /**
     * Descargar un archivo adjunto
     */
    public function downloadAttachment(string $messageId, string $attachmentId)
    {
        $message = Message::findOrFail($messageId);
        $attachment = MessageAttachment::findOrFail($attachmentId);
        
        // Verificar permisos (solo el remitente o destinatario pueden descargar)
        if ($message->sender_id != Auth::id() && $message->recipient_id != Auth::id()) {
            return redirect()->route('admin.messages.index')
                ->with('error', 'No tienes permiso para descargar este archivo');
        }
        
        if ($attachment->message_id != $message->id) {
            return redirect()->route('admin.messages.show', $message->id)
                ->with('error', 'El archivo solicitado no pertenece a este mensaje');
        }
        
        return Storage::disk('public')->download($attachment->filepath, $attachment->filename);
    }
    
    /**
     * Eliminar un archivo adjunto
     */
    public function removeAttachment(string $messageId, string $attachmentId)
    {
        $message = Message::findOrFail($messageId);
        $attachment = MessageAttachment::findOrFail($attachmentId);
        
        // Solo el remitente puede eliminar archivos adjuntos
        if ($message->sender_id != Auth::id()) {
            return redirect()->route('admin.messages.show', $message->id)
                ->with('error', 'No tienes permiso para eliminar este archivo');
        }
        
        if ($attachment->message_id != $message->id) {
            return redirect()->route('admin.messages.show', $message->id)
                ->with('error', 'El archivo solicitado no pertenece a este mensaje');
        }
        
        Storage::disk('public')->delete($attachment->filepath);
        $attachment->delete();
        
        return redirect()->route('admin.messages.show', $message->id)
            ->with('success', 'Archivo eliminado exitosamente');
    }



}
