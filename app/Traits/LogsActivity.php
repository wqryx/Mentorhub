<?php

namespace App\Traits;

use App\Helpers\ActivityHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            static::logCreated($model);
        });

        static::updated(function (Model $model) {
            static::logUpdated($model);
        });

        static::deleted(function (Model $model) {
            static::logDeleted($model);
        });
        
        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model) {
                static::logRestored($model);
            });
        }
    }

    /**
     * Registra la creación del modelo.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected static function logCreated(Model $model)
    {
        $user = Auth::user();
        $modelName = static::getModelDisplayName();
        $description = $user 
            ? "El usuario {$user->name} ha creado {$modelName} #{$model->getKey()}" 
            : "Se ha creado {$modelName} #{$model->getKey()}";
            
        ActivityHelper::logCreated($model, $description);
    }

    /**
     * Registra la actualización del modelo.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected static function logUpdated(Model $model)
    {
        if (count($model->getDirty()) > 0) {
            $user = Auth::user();
            $modelName = static::getModelDisplayName();
            $description = $user 
                ? "El usuario {$user->name} ha actualizado {$modelName} #{$model->getKey()}" 
                : "Se ha actualizado {$modelName} #{$model->getKey()}";
                
            ActivityHelper::logUpdated($model, $description);
        }
    }

    /**
     * Registra la eliminación del modelo.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected static function logDeleted(Model $model)
    {
        $user = Auth::user();
        $modelName = static::getModelDisplayName();
        $description = $user 
            ? "El usuario {$user->name} ha eliminado {$modelName} #{$model->getKey()}" 
            : "Se ha eliminado {$modelName} #{$model->getKey()}";
            
        ActivityHelper::logDeleted($model, $description);
    }

    /**
     * Registra la restauración del modelo.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected static function logRestored(Model $model)
    {
        $user = Auth::user();
        $modelName = static::getModelDisplayName();
        $description = $user 
            ? "El usuario {$user->name} ha restaurado {$modelName} #{$model->getKey()}" 
            : "Se ha restaurado {$modelName} #{$model->getKey()}";
            
        ActivityHelper::log('restored', $description, $model);
    }

    /**
     * Obtiene el nombre para mostrar del modelo.
     *
     * @return string
     */
    protected static function getModelDisplayName()
    {
        if (property_exists(static::class, 'activityLogName')) {
            return static::$activityLogName;
        }
        
        $reflection = new \ReflectionClass(static::class);
        return $reflection->getShortName();
    }
}
