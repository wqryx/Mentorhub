<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Verificar si la tabla de tareas existe
if (!Schema::hasTable('tasks')) {
    echo "La tabla 'tasks' no existe en la base de datos.\n";
    exit;
}

// Mostrar la estructura de la tabla tasks
echo "Estructura de la tabla 'tasks':\n";
echo "-------------------------------\n";
$columns = DB::select('DESCRIBE tasks');
foreach ($columns as $column) {
    echo "- {$column->Field}: {$column->Type} " . ($column->Null === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
}

echo "\n";

// Verificar si hay tareas en la base de datos
$tasksCount = DB::table('tasks')->count();
echo "Total de tareas en la base de datos: $tasksCount\n\n";

if ($tasksCount > 0) {
    // Mostrar las primeras 5 tareas como ejemplo
    echo "Ejemplo de tareas (máximo 5):\n";
    echo "-------------------------------\n";
    $sampleTasks = DB::table('tasks')->limit(5)->get();
    
    foreach ($sampleTasks as $task) {
        echo "ID: {$task->id}\n";
        echo "Título: {$task->title}\n";
        echo "Descripción: " . (isset($task->description) ? $task->description : 'N/A') . "\n";
        echo "Curso ID: " . (isset($task->course_id) ? $task->course_id : 'N/A') . "\n";
        echo "Fecha de entrega: " . (isset($task->due_date) ? $task->due_date : 'N/A') . "\n";
        echo "Estado: " . (isset($task->status) ? $task->status : 'N/A') . "\n";
        echo "-------------------------------\n";
    }
}

// Verificar si hay tareas asignadas al estudiante con ID 3
echo "\nVerificando tareas asignadas al estudiante con ID 3...\n";

try {
    $studentTasks = DB::table('task_user')
        ->where('user_id', 3)
        ->join('tasks', 'task_user.task_id', '=', 'tasks.id')
        ->join('courses', 'tasks.course_id', '=', 'courses.id')
        ->select(
            'tasks.id as task_id',
            'tasks.title as task_title',
            'tasks.due_date',
            'tasks.status',
            'courses.name as course_name'
        )
        ->get();
    
    echo "Total de tareas asignadas al estudiante: " . $studentTasks->count() . "\n\n";
    
    if ($studentTasks->isNotEmpty()) {
        echo "Detalles de las tareas asignadas:\n";
        echo "--------------------------------\n";
        
        foreach ($studentTasks as $task) {
            echo "- Tarea: {$task->task_title} (ID: {$task->task_id})\n";
            echo "  Curso: {$task->course_name}\n";
            echo "  Fecha de entrega: {$task->due_date}\n";
            echo "  Estado: {$task->status}\n\n";
        }
    } else {
        echo "El estudiante no tiene tareas asignadas.\n";
    }
    
} catch (\Exception $e) {
    echo "Error al consultar las tareas del estudiante: " . $e->getMessage() . "\n";
    
    // Verificar si la tabla task_user existe
    if (!Schema::hasTable('task_user')) {
        echo "\nLa tabla 'task_user' no existe en la base de datos.\n";
    }
}
