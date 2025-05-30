<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MentorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::guard('web')->check()) {
            // Limpiar completamente la sesión
            $request->session()->flush();
            Auth::guard('web')->logout();
            
            // Redirigir a la página de login
            return redirect()->guest(route('login'))
                ->with('error', 'Por favor, inicia sesión para acceder a esta página');
        }

        // Obtener el usuario actual
        $user = Auth::guard('web')->user();
        
        // Verificar si el usuario existe
        if (!$user) {
            // Limpiar completamente la sesión
            $request->session()->flush();
            Auth::guard('web')->logout();
            
            return redirect()->guest(route('login'))
                ->with('error', 'Sesión inválida');
        }

        // Verificar si el usuario tiene el rol de mentor
        if (!$user->hasRole('mentor')) {
            // Limpiar completamente la sesión
            $request->session()->flush();
            Auth::guard('web')->logout();
            
            return redirect()->guest(route('login'))
                ->with('error', 'No tienes permisos de mentor');
        }

        return $next($request);
    }
}
