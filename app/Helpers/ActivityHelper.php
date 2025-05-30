<?php

namespace App\Helpers;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityHelper
{
    /**
     * Registra una actividad en el sistema.
     *
     * @param string $action La acción realizada (crear, actualizar, eliminar, etc.)
     * @param string $description Descripción de la actividad
     * @param \Illuminate\Database\Eloquent\Model|null $model El modelo afectado
     * @param array|null $properties Propiedades adicionales para registrar
     * @return \App\Models\ActivityLog|null
     */
    public static function log($action, $description, $model = null, $properties = null)
    {
        try {
            return ActivityLogService::log($action, $description, $model, $properties);
        } catch (\Exception $e) {
            \Log::error('Error al registrar actividad: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'action' => $action,
                'description' => $description
            ]);
            
            return null;
        }
    }
    
    /**
     * Registra una actividad de creación.
     *
     * @param \Illuminate\Database\Eloquent\Model $model El modelo creado
     * @param string|null $description Descripción opcional
     * @param array|null $properties Propiedades adicionales
     * @return \App\Models\ActivityLog|null
     */
    public static function logCreated(Model $model, $description = null, $properties = null)
    {
        try {
            return ActivityLogService::logCreated($model, $description, $properties);
        } catch (\Exception $e) {
            \Log::error('Error al registrar actividad de creación: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'model' => get_class($model),
                'model_id' => $model->getKey()
            ]);
            
            return null;
        }
    }
    
    /**
     * Registra una actividad de actualización.
     *
     * @param \Illuminate\Database\Eloquent\Model $model El modelo actualizado
     * @param string|null $description Descripción opcional
     * @param array|null $properties Propiedades adicionales
     * @return \App\Models\ActivityLog|null
     */
    public static function logUpdated(Model $model, $description = null, $properties = null)
    {
        try {
            return ActivityLogService::logUpdated($model, $description, $properties);
        } catch (\Exception $e) {
            \Log::error('Error al registrar actividad de actualización: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'model' => get_class($model),
                'model_id' => $model->getKey()
            ]);
            
            return null;
        }
    }
    
    /**
     * Registra una actividad de eliminación.
     *
     * @param \Illuminate\Database\Eloquent\Model $model El modelo eliminado
     * @param string|null $description Descripción opcional
     * @param array|null $properties Propiedades adicionales
     * @return \App\Models\ActivityLog|null
     */
    public static function logDeleted(Model $model, $description = null, $properties = null)
    {
        try {
            return ActivityLogService::logDeleted($model, $description, $properties);
        } catch (\Exception $e) {
            \Log::error('Error al registrar actividad de eliminación: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'model' => get_class($model),
                'model_id' => $model->getKey()
            ]);
            
            return null;
        }
    }
    
    /**
     * Registra una actividad personalizada.
     *
     * @param string $action Tipo de acción
     * @param string $description Descripción de la acción
     * @param array|null $properties Propiedades adicionales
     * @return \App\Models\ActivityLog|null
     */
    public static function logCustom($action, $description, $properties = null)
    {
        try {
            return ActivityLogService::log($action, $description, null, $properties);
        } catch (\Exception $e) {
            \Log::error('Error al registrar actividad personalizada: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'action' => $action,
                'description' => $description
            ]);
            
            return null;
        }
    }
}
