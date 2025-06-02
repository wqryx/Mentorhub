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
        $this->middleware('role:student');
    }

    /**
     * Mostrar todas las notificaciones del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->orWhere('is_global', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('student.notifications.index', compact('notifications'));
    }

    /**
     * Mostrar una notificación específica.
     *
     * @param int $notification
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($notification)
    {
        $user = Auth::user();
        
        $notification = Notification::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('is_global', true);
            })
            ->findOrFail($notification);
        
        // Marcar como leída si no lo está
        if (!$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
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
        
        Notification::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('is_global', true);
            })
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }
}
