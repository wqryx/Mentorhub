<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// 1. Obtener un curso existente
$course = DB::table('courses')->first();
if (!$course) die("No hay cursos en la base de datos.\n");
echo "Curso: {$course->name} (ID: {$course->id})\n";

// 2. Obtener un módulo para el curso
$module = DB::table('modules')
    ->where('course_id', $course->id)
    ->orderBy('order')
    ->first();
    
if (!$module) die("No hay módulos para el curso ID: {$course->id}\n");
echo "Módulo: {$module->title} (ID: {$module->id})\n";

// 3. Obtener un usuario (profesor/mentor)
$teacher = DB::table('model_has_roles')
    ->whereIn('role_id', [1, 2])
    ->join('users', 'model_has_roles.model_id', '=', 'users.id')
    ->select('users.id', 'users.name')
    ->first();
    
if (!$teacher) {
    $teacher = DB::table('users')->first();
    echo "Advertencia: Usando usuario por defecto: {$teacher->name} (ID: {$teacher->id})\n";
} else {
    echo "Profesor: {$teacher->name} (ID: {$teacher->id})\n";
}

// 4. Intentar crear una tarea simple
try {
    $taskData = [
        'title' => "Tarea de prueba simple",
        'description' => 'Esta es una tarea de prueba simple',
        'due_date' => date('Y-m-d H:i:s', strtotime('+1 week')),
        'status' => 'pending',
        'priority' => 'medium',
        'user_id' => $teacher->id,
        'course_id' => $course->id,
        'module_id' => $module->id,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    
    echo "\nIntentando crear tarea con los siguientes datos:\n";
    print_r($taskData);
    
    $taskId = DB::table('tasks')->insertGetId($taskData);
    echo "\n¡Éxito! Tarea creada con ID: $taskId\n";
    
} catch (Exception $e) {
    echo "\nError al crear la tarea: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error SQL si está disponible
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        $pdoException = $e->getPrevious();
        echo "SQL Error Code: " . $pdoException->getCode() . "\n";
        echo "SQL Error Message: " . $pdoException->getMessage() . "\n";
        
        if (method_exists($pdoException, 'errorInfo')) {
            $errorInfo = $pdoException->errorInfo;
            echo "SQL State: " . ($errorInfo[0] ?? 'N/A') . "\n";
            echo "Driver Error Code: " . ($errorInfo[1] ?? 'N/A') . "\n";
            echo "Driver Error Message: " . ($errorInfo[2] ?? 'N/A') . "\n";
        }
    }
}
