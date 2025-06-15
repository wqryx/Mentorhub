<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
    // 1. Verificar tablas necesarias
    showMessage("1. Verificando tablas necesarias:", 'info');
    $tables = ['tasks', 'courses', 'modules', 'course_user', 'users'];
    foreach ($tables as $table) {
        $exists = Schema::hasTable($table) ? '✓' : '✗';
        showMessage("   {$exists} Tabla {$table}", Schema::hasTable($table) ? 'success' : 'error');
    }

    // 2. Obtener un curso de ejemplo
    showMessage("\n2. Obteniendo un curso de ejemplo:", 'info');
    $course = DB::table('courses')->first();
    
    if (!$course) {
        throw new Exception("No se encontraron cursos en la base de datos.");
    }
    
    showMessage("   Curso encontrado: {$course->name} (ID: {$course->id}, Código: {$course->code})", 'success');

    // 3. Obtener un módulo para el curso
    showMessage("\n3. Obteniendo un módulo para el curso:", 'info');
    $module = DB::table('modules')
        ->where('course_id', $course->id)
        ->orderBy('order')
        ->first();
    
    if (!$module) {
        throw new Exception("No se encontraron módulos para el curso ID: {$course->id}");
    }
    
    showMessage("   Módulo encontrado: {$module->title} (ID: {$module->id}, Orden: {$module->order})", 'success');

    // 4. Obtener un profesor/mentor
    showMessage("\n4. Obteniendo un profesor/mentor:", 'info');
    $teacher = DB::table('model_has_roles')
        ->whereIn('role_id', [1, 2])
        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        ->select('users.id', 'users.name', 'users.email')
        ->first();
    
    if (!$teacher) {
        // Si no hay profesores, usar el primer usuario disponible
        $teacher = DB::table('users')->first();
        showMessage("   No se encontraron profesores, usando el usuario: {$teacher->name} (ID: {$teacher->id})", 'warning');
    } else {
        showMessage("   Profesor encontrado: {$teacher->name} (ID: {$teacher->id}, Email: {$teacher->email})", 'success');
    }

    // 5. Crear una tarea de prueba
    showMessage("\n5. Intentando crear una tarea de prueba:", 'info');
    
    $taskData = [
        'title' => "[PRUEBA] Tarea de prueba",
        'description' => 'Esta es una tarea de prueba',
        'due_date' => Carbon::now()->addWeek()->format('Y-m-d H:i:s'),
        'status' => 'pending',
        'priority' => 'medium',
        'user_id' => $teacher->id,
        'course_id' => $course->id,
        'module_id' => $module->id,
        'created_at' => now(),
        'updated_at' => now(),
    ];
    
    showMessage("   Datos de la tarea a crear:", 'info');
    echo json_encode($taskData, JSON_PRETTY_INT | JSON_UNESCAPED_UNICODE) . "\n";
    
    // 6. Verificar restricciones de clave foránea
    showMessage("\n6. Verificando restricciones de clave foránea:", 'info');
    
    // Verificar que el usuario existe
    $userExists = DB::table('users')->where('id', $teacher->id)->exists();
    showMessage("   Usuario con ID {$teacher->id} existe: " . ($userExists ? 'Sí' : 'No'), $userExists ? 'success' : 'error');
    
    // Verificar que el curso existe
    $courseExists = DB::table('courses')->where('id', $course->id)->exists();
    showMessage("   Curso con ID {$course->id} existe: " . ($courseExists ? 'Sí' : 'No'), $courseExists ? 'success' : 'error');
    
    // Verificar que el módulo existe y pertenece al curso
    $moduleExists = DB::table('modules')
        ->where('id', $module->id)
        ->where('course_id', $course->id)
        ->exists();
    showMessage("   Módulo con ID {$module->id} existe y pertenece al curso: " . ($moduleExists ? 'Sí' : 'No'), $moduleExists ? 'success' : 'error');
    
    // 7. Intentar crear la tarea
    showMessage("\n7. Intentando crear la tarea...", 'info');
    
    try {
        DB::beginTransaction();
        
        $taskId = DB::table('tasks')->insertGetId($taskData);
        
        showMessage("   ✓ Tarea creada exitosamente con ID: $taskId", 'success');
        
        // 8. Asignar la tarea a los estudiantes del curso
        $students = DB::table('course_user')
            ->where('course_id', $course->id)
            ->pluck('user_id');
        
        if ($students->isEmpty()) {
            showMessage("   - No hay estudiantes inscritos en este curso para asignar la tarea.", 'warning');
        } else {
            $assignments = [];
            $now = now();
            
            foreach ($students as $studentId) {
                $assignments[] = [
                    'task_id' => $taskId,
                    'user_id' => $studentId,
                    'status' => 'pending',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            DB::table('task_user')->insert($assignments);
            showMessage("   ✓ Tarea asignada a " . count($students) . " estudiante(s)", 'success');
        }
        
        DB::commit();
        
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
    
    showMessage("\n¡Proceso completado con éxito!", 'success');
    
} catch (\Exception $e) {
    showMessage("\nError: " . $e->getMessage(), 'error');
    
    // Mostrar información detallada del error
    if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
        showMessage("\nDetalles del error SQL:", 'error');
        showMessage("Código: " . $e->getCode(), 'error');
        
        // Intentar obtener más detalles del error
        $errorInfo = $e->errorInfo ?? [];
        if (!empty($errorInfo)) {
            showMessage("SQL Error Code: " . ($errorInfo[1] ?? 'N/A'), 'error');
            showMessage("SQL Error Message: " . ($errorInfo[2] ?? 'N/A'), 'error');
        }
    }
    
    exit(1);
}

echo "\n";
