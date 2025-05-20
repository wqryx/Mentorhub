<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        // No se necesita middleware aquí, ya que está definido en las rutas
    }
    public function showRegistrationForm()
    {
        // Asegurarse de que no haya sesión activa
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            request()->session()->flush();
        }
        
        // Limpiar cualquier mensaje de sesión
        request()->session()->forget('error');
        request()->session()->forget('success');
        
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Asignar el rol de admin
            $role = Role::where('name', 'Admin')->first();
            if ($role) {
                $user->roles()->attach($role->id);
            } else {
                return redirect()->back()->with('error', 'No se pudo asignar el rol de administrador');
            }

            return redirect()->route('admin.login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar el administrador: ' . $e->getMessage());
        }
    }

    public function showLoginForm()
    {
        return view('admin.login');
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

            // Verificar si el usuario tiene el rol de admin
            if (!$user->hasRole('Admin')) {
                throw new \Exception('No tienes permisos de administrador');
            }

            // Regenerar la sesión para prevenir ataques de sesión fija
            $request->session()->regenerate();
            
            // Limpiar cualquier mensaje de error previo
            $request->session()->forget('error');
            
            // Redirigir al dashboard
            return redirect()->route('admin.dashboard')
                ->with('success', '¡Bienvenido al dashboard de administrador!');

        } catch (\Exception $e) {
            // Limpiar la sesión en caso de error
            $request->session()->flush();
            
            return back()->withErrors([
                'email' => $e->getMessage(),
            ])->onlyInput('email');
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('welcome');
    }

    public function dashboard()
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Por favor, inicia sesión para acceder al dashboard');
        }

        // Verificar si el usuario tiene el rol de admin
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('admin.login')
                ->with('error', 'No tienes permisos de administrador');
        }

        // Obtener el usuario actual
        $user = auth()->user();

        // Obtener información de sesiones
        $activeSessions = 1; // Como solo estamos en una sesión, mostramos 1

        // Pasar el usuario y las sesiones a la vista
        return view('admin.dashboard', compact('user', 'activeSessions'));
    }
}
