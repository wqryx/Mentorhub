<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión
     */
    public function showLoginForm()
    {
        // Si ya hay sesión, limpiarla para evitar conflictos
        if (session()->has('user')) {
            session()->flush();
        }
        
        return view('auth.login');
    }

    /**
     * Procesa el inicio de sesión
     */
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
                throw new \Exception('Las credenciales proporcionadas no coinciden con nuestros registros.');
            }

            // Obtener el usuario autenticado
            $user = Auth::user();
            
            if (!$user) {
                throw new \Exception('No se pudo obtener la información del usuario.');
            }

            // Regenerar la sesión para seguridad
            $request->session()->regenerate();
            
            // Registrar información de roles para depuración
            $userRoles = $user->getRoleNames();
            Log::info('Usuario logueado: ' . $user->email . ' con roles: ' . json_encode($userRoles));
            
            // Redirección basada en roles
            if ($user->hasRole('admin')) {
                return redirect()->intended('/dashboard/admin')
                    ->with('success', '¡Bienvenido al panel de administración!');
            }
            
            if ($user->hasRole('mentor')) {
                return redirect()->intended('/dashboard/mentor')
                    ->with('success', '¡Bienvenido al panel de mentor!');
            }
            
            if ($user->hasRole('student')) {
                return redirect()->intended('/dashboard/student')
                    ->with('success', '¡Bienvenido a tu dashboard de estudiante!');
            }
            
            // Si el usuario no tiene un rol asignado
            Log::warning('Usuario sin rol definido: ' . $user->email);
            return redirect()->route('home')
                ->with('warning', 'Tu cuenta no tiene un rol asignado. Por favor, contacta al administrador.');
            
        } catch (\Exception $e) {
            Log::error('Error de autenticación: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => $e->getMessage(),
            ])->onlyInput('email');
        }
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout(Request $request)
    {
        // Guardar nombre de usuario para mensaje personalizado
        $userName = Auth::user() ? Auth::user()->name : '';
        
        // Cerrar sesión
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('status', '¡Hasta pronto, ' . $userName . '! Has cerrado sesión correctamente.');
    }

    /**
     * Muestra el formulario de inicio de sesión para administradores
     */
    public function showAdminLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Maneja el inicio de sesión de administradores
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_admin' => true])) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas o no tiene permisos de administrador.',
        ]);
    }

    /**
     * Cierra la sesión del administrador
     */
    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
