<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Registra una actividad en el sistema.
     *
     * @param string $action La acción realizada (crear, actualizar, eliminar, etc.)
     * @param string $description Descripción de la actividad
     * @param \Illuminate\Database\Eloquent\Model|null $model El modelo afectado
     * @param array|null $properties Propiedades adicionales para registrar
     * @return \App\Models\ActivityLog
     */
    public static function log(string $action, string $description, ?Model $model = null, ?array $properties = null): ActivityLog
    {
        $userId = Auth::id();
        
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];
        
        if ($model) {
            $data['model_type'] = get_class($model);
            $data['model_id'] = $model->getKey();
        }
        
        if ($properties) {
            $data['properties'] = $properties;
        }
        
        return ActivityLog::create($data);
    }
    
    /**
     * Registra una actividad de creación.
     *
     * @param \Illuminate\Database\Eloquent\Model $model El modelo creado
     * @param string|null $description Descripción opcional
     * @param array|null $properties Propiedades adicionales
     * @return \App\Models\ActivityLog
     */
    public static function logCreated(Model $model, ?string $description = null, ?array $properties = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = $description ?? "Se ha creado {$modelName} #{$model->getKey()}";
        
        return self::log('created', $description, $model, $properties);
    }
    
    /**
     * Registra una actividad de actualización.
     *
     * @param \Illuminate\Database\Eloquent\Model $model El modelo actualizado
     * @param string|null $description Descripción opcional
     * @param array|null $properties Propiedades adicionales
     * @return \App\Models\ActivityLog
     */
    public static function logUpdated(Model $model, ?string $description = null, ?array $properties = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = $description ?? "Se ha actualizado {$modelName} #{$model->getKey()}";
        
        // Registrar los cambios en las propiedades
        if (!$properties && method_exists($model, 'getDirty')) {
            $properties = [
                'old' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'new' => $model->getDirty()
            ];
        }
        
        return self::log('updated', $description, $model, $properties);
    }
    
    /**
     * Registra una actividad de eliminación.
     *
     * @param \Illuminate\Database\Eloquent\Model $model El modelo eliminado
     * @param string|null $description Descripción opcional
     * @param array|null $properties Propiedades adicionales
     * @return \App\Models\ActivityLog
     */
    public static function logDeleted(Model $model, ?string $description = null, ?array $properties = null): ActivityLog
    {
        $modelName = class_basename($model);
        $description = $description ?? "Se ha eliminado {$modelName} #{$model->getKey()}";
        
        return self::log('deleted', $description, $model, $properties);
    }
    
    /**
     * Registra una actividad de inicio de sesión.
     *
     * @param \App\Models\User $user El usuario que inició sesión
     * @return \App\Models\ActivityLog
     */
    public static function logLogin($user): ActivityLog
    {
        return self::log(
            'login',
            "El usuario {$user->name} ha iniciado sesión",
            $user
        );
    }
    
    /**
     * Registra una actividad de cierre de sesión.
     *
     * @param \App\Models\User $user El usuario que cerró sesión
     * @return \App\Models\ActivityLog
     */
    public static function logLogout($user): ActivityLog
    {
        return self::log(
            'logout',
            "El usuario {$user->name} ha cerrado sesión",
            $user
        );
    }
}
