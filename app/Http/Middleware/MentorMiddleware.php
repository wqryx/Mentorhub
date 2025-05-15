<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MentorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role->name === 'Mentor') {
            return $next($request);
        }

        abort(403, 'Acceso no autorizado. Se requiere rol de Mentor.');
    }
}
