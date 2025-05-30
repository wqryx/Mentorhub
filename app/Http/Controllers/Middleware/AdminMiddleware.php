<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Verificar si el usuario está autenticado
        if (!Auth::guard('web')->check()) {
            // Limpiar completamente la sesión
            $request->session()->flush();
            Auth::guard('web')->logout();
            
            // Redirigir a la página de login
            return redirect()->guest(route('admin.login'))
                ->with('error', 'Por favor, inicia sesión para acceder a esta página');
        }

        // Obtener el usuario actual
        $user = Auth::guard('web')->user();
        
        // Verificar si el usuario existe
        if (!$user) {
            // Limpiar completamente la sesión
            $request->session()->flush();
            Auth::guard('web')->logout();
            
            return redirect()->guest(route('admin.login'))
                ->with('error', 'Sesión inválida');
        }

        // Verificar si el usuario tiene el rol de admin
        if (!$user->hasRole('admin')) {
            // Limpiar completamente la sesión
            $request->session()->flush();
            Auth::guard('web')->logout();
            
            return redirect()->guest(route('admin.login'))
                ->with('error', 'No tienes permisos de administrador');
        }

        // Limpiar cualquier mensaje de error previo
        $request->session()->forget('error');
        $request->session()->forget('success');
        
        // Regenerar la sesión para prevenir ataques de fijación de sesión
        $request->session()->regenerate();

        return $next($request);
    }
}
