<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // El middleware de roles ha sido eliminado temporalmente
    }

    /**
     * Mostrar todas las notificaciones del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's notifications using the polymorphic relationship
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        // Mark all unread notifications as read
        $user->unreadNotifications->markAsRead();
        
        return view('student.notifications.index', compact('notifications'));
    }

    /**
     * Mostrar una notificación específica.
     *
     * @param int $notification
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($notificationId)
    {
        $user = Auth::user();
        
        // Find the notification for the authenticated user
        $notification = $user->notifications()->findOrFail($notificationId);
        
        // Mark the notification as read if it's unread
        if ($notification->unread()) {
            $notification->markAsRead();
        }
        
        return view('student.notifications.show', compact('notification'));
    }

    /**
     * Marcar todas las notificaciones como leídas.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        // Mark all unread notifications as read
        $user->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }
}
