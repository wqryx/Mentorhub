<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Models\ActivityLog;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if (auth()->check()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'subject' => $request->route()->getName(),
                'description' => "Usuario " . auth()->user()->name . " accedió a la ruta: " . $request->route()->getName()
            ]);
        }

        return $response;
    }
}
