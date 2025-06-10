<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\MentorApprovalRequest;
use App\Notifications\AccountApproved;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Muestra la vista de registro.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Maneja una solicitud de registro entrante.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,mentor',
            'terms' => 'required|accepted',
        ]);

        // Crear el usuario con estado inactivo por defecto
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->role === 'student', // Los estudiantes son activados automáticamente
        ]);

        // Asignar el rol al usuario
        $user->assignRole($request->role);

        // Si es mentor, requiere aprobación
        if ($request->role === 'mentor') {
            // Notificar a los administradores
            $admins = User::role('admin')->get();
            Notification::send($admins, new MentorApprovalRequest($user));
            
            // Redirigir con mensaje de aprobación pendiente
            return redirect()->route('register')
                ->with('status', 'Tu solicitud de registro como mentor ha sido recibida. Te notificaremos por correo electrónico una vez que sea aprobada por un administrador.');
        }

        // Si es estudiante, notificar aprobación automática
        $user->notify(new AccountApproved('student'));
        
        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
