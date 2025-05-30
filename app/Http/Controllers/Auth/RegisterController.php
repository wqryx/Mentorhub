<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro para administradores
     */
    public function showAdminRegisterForm()
    {
        return view('admin.register');
    }

    /**
     * Crea un nuevo usuario administrador
     */
    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Asignar rol de administrador
        $user->assignRole('admin');

        // Iniciar sesión automáticamente
        auth()->login($user);

        return redirect()->route('admin.dashboard')
            ->with('status', '¡Administrador registrado exitosamente!');
    }
}
