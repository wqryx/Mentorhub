<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function showMessage($message, $type = 'info') {
    $colors = [
        'success' => '0;32', // Verde
        'error' => '0;31',   // Rojo
        'warning' => '0;33', // Amarillo
        'info' => '0;36',    // Cian
        'default' => '0;37', // Blanco
    ];
    
    $color = $colors[$type] ?? $colors['default'];
    echo "\033[{$color}m{$message}\033[0m\n";
}

try {
    // Verificar las restricciones de clave foránea en la tabla tasks
    $foreignKeys = DB::select("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME, 
            CONSTRAINT_NAME, 
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE 
            REFERENCED_TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'tasks'
            AND REFERENCED_TABLE_NAME IS NOT NULL
    ");

    if (empty($foreignKeys)) {
        showMessage("No se encontraron restricciones de clave foránea en la tabla 'tasks'.", 'warning');
    } else {
        showMessage("Restricciones de clave foránea en la tabla 'tasks':", 'info');
        foreach ($foreignKeys as $fk) {
            echo "- {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME} (Nombre: {$fk->CONSTRAINT_NAME})\n";
        }
    }

    // Verificar las restricciones de clave foránea en la tabla task_user
    $taskUserForeignKeys = DB::select("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME, 
            CONSTRAINT_NAME, 
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE 
            REFERENCED_TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'task_user'
            AND REFERENCED_TABLE_NAME IS NOT NULL
    ");

    if (empty($taskUserForeignKeys)) {
        showMessage("\nNo se encontraron restricciones de clave foránea en la tabla 'task_user'.", 'warning');
    } else {
        showMessage("\nRestricciones de clave foránea en la tabla 'task_user':", 'info');
        foreach ($taskUserForeignKeys as $fk) {
            echo "- {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME} (Nombre: {$fk->CONSTRAINT_NAME})\n";
        }
    }

    // Verificar si hay datos en las tablas relacionadas
    showMessage("\nVerificando datos en tablas relacionadas:", 'info');
    
    // Verificar usuarios con rol de profesor/mentor
    $teachers = DB::table('model_has_roles')
        ->whereIn('role_id', [1, 2]) // IDs de admin y mentor
        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        ->select('users.id', 'users.name', 'users.email')
        ->get();
    
    if ($teachers->isEmpty()) {
        showMessage("- No se encontraron profesores/mentores en la base de datos.", 'warning');
        
        // Mostrar los primeros 3 usuarios disponibles
        $users = DB::table('users')->take(3)->get(['id', 'name', 'email']);
        showMessage("  Usuarios disponibles:", 'info');
        foreach ($users as $user) {
            echo "  - ID: {$user->id}, Nombre: {$user->name}, Email: {$user->email}\n";
        }
    } else {
        showMessage("- Profesores/mentores encontrados:", 'success');
        foreach ($teachers as $teacher) {
            echo "  - ID: {$teacher->id}, Nombre: {$teacher->name}, Email: {$teacher->email}\n";
        }
    }
    
    // Verificar módulos (ya que tasks tiene una clave foránea a modules)
    $modules = DB::table('modules')->count();
    if ($modules === 0) {
        showMessage("- No hay módulos en la base de datos.", 'warning');
        showMessage("  La tabla 'tasks' tiene una restricción de clave foránea a 'modules' que no puede ser nula.", 'error');
    } else {
        showMessage("- Se encontraron $modules módulos en la base de datos.", 'success');
    }
    
    // Verificar cursos
    $courses = DB::table('courses')->count();
    if ($courses === 0) {
        showMessage("- No hay cursos en la base de datos.", 'error');
    } else {
        showMessage("- Se encontraron $courses cursos en la base de datos.", 'success');
    }
    
    // Verificar estudiantes inscritos
    $enrollments = DB::table('course_user')->count();
    if ($enrollments === 0) {
        showMessage("- No hay estudiantes inscritos en ningún curso.", 'warning');
    } else {
        showMessage("- Se encontraron $enrollments inscripciones de estudiantes a cursos.", 'success');
    }
    
} catch (\Exception $e) {
    showMessage("\nError: " . $e->getMessage(), 'error');
    exit(1);
}

echo "\n";
