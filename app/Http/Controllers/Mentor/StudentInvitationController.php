<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\StudentInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class StudentInvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Muestra el formulario para invitar a un nuevo estudiante
     */
    public function create()
    {
        return view('mentor.students.invite');
    }

    /**
     * Envía una invitación a un nuevo estudiante
     */
    public function invite(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email|unique:invitations,email',
        ]);

        // Verificar si el usuario ya existe
        if (User::where('email', $request->email)->exists()) {
            return redirect()->route('mentor.students.create')
                ->withInput()
                ->with('error', 'Este correo ya está registrado como usuario.');
        }

        // Crear invitación
        $invitation = new \App\Models\Invitation([
            'email' => $request->email,
            'mentor_id' => Auth::id(),
            'token' => \Illuminate\Support\Str::random(32),
            'expires_at' => now()->addDays(7)
        ]);
        
        $invitation->save();

        // Enviar notificación por correo
        try {
            Notification::route('mail', $request->email)
                ->notify(new StudentInvitationNotification($invitation));
                
            return redirect()->route('mentor.students')
                ->with('success', 'Invitación enviada correctamente a ' . $request->email);
                
        } catch (\Exception $e) {
            \Log::error('Error al enviar invitación: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al enviar la invitación. Por favor, inténtalo de nuevo.');
        }
    }
}
