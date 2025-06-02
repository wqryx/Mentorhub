<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Procesar la solicitud
        $response = $next($request);

        // Solo registrar acciones POST, PUT, PATCH, DELETE
        if (!$this->shouldLogRequest($request)) {
            return $response;
        }

        // Obtener información de la solicitud
        $method = $request->method();
        $path = $request->path();
        $user = Auth::user();

        // Determinar la acción basada en el método HTTP
        $action = $this->mapMethodToAction($method);
        
        // Determinar la descripción basada en la ruta
        $description = $this->generateDescription($request, $action);

        // Registrar la actividad (deshabilitado temporalmente)
        if ($user && $action && $description) {
            // Solo registrar en el log del sistema en lugar de usar el servicio
            Log::info('Actividad: ' . $action . ' - ' . $description, [
                'user_id' => $user->id,
                'route' => $path,
                'method' => $method,
                'ip' => $request->ip(),
            ]);
        }

        return $response;
    }

    /**
     * Determina si la solicitud debe ser registrada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldLogRequest(Request $request)
    {
        // Solo registrar métodos que modifican datos
        $methods = ['POST', 'PUT', 'PATCH', 'DELETE'];
        return in_array($request->method(), $methods);
    }

    /**
     * Mapea el método HTTP a una acción descriptiva.
     *
     * @param  string  $method
     * @return string|null
     */
    protected function mapMethodToAction($method)
    {
        $map = [
            'POST' => 'created',
            'PUT' => 'updated',
            'PATCH' => 'updated',
            'DELETE' => 'deleted',
        ];

        return $map[$method] ?? null;
    }

    /**
     * Genera una descripción basada en la ruta y la acción.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $action
     * @return string
     */
    protected function generateDescription(Request $request, $action)
    {
        $path = $request->path();
        $segments = explode('/', $path);
        
        // Intentar determinar el recurso
        $resource = end($segments);
        if (is_numeric($resource) && count($segments) > 1) {
            $resource = $segments[count($segments) - 2];
        }
        
        // Singularizar el recurso si es posible (regla simple)
        if (substr($resource, -1) === 's') {
            $resource = substr($resource, 0, -1);
        }
        
        return ucfirst($action) . ' ' . $resource;
    }
}
