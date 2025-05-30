<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
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

        // Registrar la actividad
        if ($user && $action && $description) {
            ActivityLogService::log(
                $action,
                $description,
                null,
                [
                    'route' => $path,
                    'method' => $method,
                    'ip' => $request->ip(),
                ]
            );
        }

        return $response;
    }

    /**
     * Determina si la solicitud debe ser registrada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function shouldLogRequest(Request $request): bool
    {
        // Ignorar solicitudes de assets, API y debug
        if (
            $request->is('api/*') || 
            $request->is('_debugbar/*') || 
            $request->is('livewire/*') ||
            $request->is('assets/*') ||
            $request->is('css/*') ||
            $request->is('js/*') ||
            $request->is('img/*')
        ) {
            return false;
        }

        // Solo registrar acciones que modifican datos
        $method = $request->method();
        return in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    /**
     * Mapea el método HTTP a una acción.
     *
     * @param  string  $method
     * @return string|null
     */
    private function mapMethodToAction(string $method): ?string
    {
        switch ($method) {
            case 'POST':
                return 'created';
            case 'PUT':
            case 'PATCH':
                return 'updated';
            case 'DELETE':
                return 'deleted';
            default:
                return null;
        }
    }

    /**
     * Genera una descripción para la actividad basada en la ruta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $action
     * @return string
     */
    private function generateDescription(Request $request, string $action): string
    {
        $path = $request->path();
        $routeName = $request->route() ? $request->route()->getName() : null;
        $user = Auth::user();
        
        // Intentar determinar el recurso basado en la ruta
        $segments = explode('/', $path);
        $resource = end($segments);
        
        if (is_numeric($resource) && count($segments) > 1) {
            $resource = $segments[count($segments) - 2];
        }
        
        $resource = str_singular(str_replace('-', ' ', $resource));
        
        // Generar descripción
        if ($routeName) {
            return "El usuario {$user->name} ha {$this->getActionVerb($action)} {$resource} mediante {$routeName}";
        }
        
        return "El usuario {$user->name} ha {$this->getActionVerb($action)} {$resource}";
    }
    
    /**
     * Obtiene el verbo para la acción.
     *
     * @param  string  $action
     * @return string
     */
    private function getActionVerb(string $action): string
    {
        switch ($action) {
            case 'created':
                return 'creado';
            case 'updated':
                return 'actualizado';
            case 'deleted':
                return 'eliminado';
            default:
                return $action;
        }
    }
}
