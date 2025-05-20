<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Asegurarse de que no haya sesión activa
        if (session()->has('user')) {
            session()->flush();
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            // Intentar autenticar al usuario
            if (!Auth::attempt([
                'email' => $request->email, 
                'password' => $request->password
            ])) {
                throw new \Exception('Credenciales inválidas');
            }

            // Obtener el usuario autenticado
            $user = Auth::user();
            
            if (!$user) {
                throw new \Exception('Usuario no encontrado después de la autenticación');
            }

            // Limpiar cualquier sesión anterior
            $request->session()->flush();
            
            // Guardar el usuario en sesión
            $request->session()->put('user', $user);
            
            // Determinar a qué dashboard redirigir según el rol
            if ($user->hasRole('student')) {
                return redirect()->route('dashboard.student')
                    ->with('success', '¡Bienvenido al dashboard de estudiante!');
            } elseif ($user->hasRole('mentor')) {
                return redirect()->route('dashboard.mentor')
                    ->with('success', '¡Bienvenido al dashboard de mentor!');
            } elseif ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard')
                    ->with('success', '¡Bienvenido al dashboard de administrador!');
            } else {
                return redirect()->route('dashboard')
                    ->with('success', '¡Bienvenido!');
            }

        } catch (\Exception $e) {
            // Limpiar la sesión en caso de error
            $request->session()->flush();
            
            return back()->withErrors([
                'email' => $e->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        // Limpiar completamente la sesión
        $request->session()->flush();
        Auth::guard('web')->logout();
        
        // Limpiar cualquier mensaje de sesión
        $request->session()->forget('error');
        $request->session()->forget('success');
        
        // Redirigir a la página de login
        return redirect()->route('admin.login');
    }
}
