<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use LogsActivity;
    
    /**
     * Nombre personalizado para los registros de actividad.
     *
     * @var string
     */
    protected static $activityLogName = 'Permiso';
}
