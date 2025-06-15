<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

// Función para mostrar mensajes con formato
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

// Función para manejar errores de base de datos
function handleDatabaseError($e) {
    showMessage("Error de base de datos: " . $e->getMessage(), 'error');
    
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        $pdoException = $e->getPrevious();
        showMessage("Código de error: " . $pdoException->getCode(), 'error');
        
        if (method_exists($pdoException, 'errorInfo')) {
            $errorInfo = $pdoException->errorInfo;
            showMessage("Mensaje del controlador: " . ($errorInfo[2] ?? 'N/A'), 'error');
        }
    }
}

try {
    // Verificar si las tablas necesarias existen
    $tables = ['tasks', 'courses', 'course_user', 'model_has_roles'];
    foreach ($tables as $table) {
        if (!Schema::hasTable($table)) {
            throw new Exception("La tabla '$table' no existe en la base de datos.");
        }
    }

    // Verificar si ya hay tareas en la base de datos
    $existingTasks = DB::table('tasks')->count();
    if ($existingTasks > 0) {
        showMessage("Ya existen $existingTasks tareas en la base de datos. Se crearán tareas adicionales.", 'info');
    }

    // Obtener los cursos disponibles
    $courses = DB::table('courses')->get(['id', 'name', 'code']);
    if ($courses->isEmpty()) {
        throw new Exception('No hay cursos disponibles para asignar tareas.');
    }

    showMessage("\nCursos disponibles para asignar tareas:", 'info');
    foreach ($courses as $course) {
        showMessage("- {$course->name} (ID: {$course->id}, Código: {$course->code})", 'info');
    }

    // Crear tareas de ejemplo para cada curso
    $tasks = [
        [
            'title' => 'Tarea 1: Configuración del entorno',
            'description' => 'Configura tu entorno de desarrollo siguiendo las instrucciones del módulo 1.',
            'due_date' => Carbon::now()->addWeek()->format('Y-m-d H:i:s'),
            'priority' => 'high',
        ],
        [
            'title' => 'Tarea 2: Ejercicios prácticos',
            'description' => 'Completa los ejercicios prácticos del módulo 1 y envía tus soluciones.',
            'due_date' => Carbon::now()->addWeeks(2)->format('Y-m-d H:i:s'),
            'priority' => 'medium',
        ],
        [
            'title' => 'Tarea 3: Proyecto final',
            'description' => 'Desarrolla un proyecto aplicando los conceptos aprendidos en el curso.',
            'due_date' => Carbon::now()->addMonth()->format('Y-m-d H:i:s'),
            'priority' => 'high',
        ],
    ];

    // Obtener el ID del profesor (usuario con rol de mentor o admin)
    $teacher = DB::table('model_has_roles')
        ->whereIn('role_id', [1, 2]) // IDs de admin y mentor
        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        ->select('users.id', 'users.name')
        ->first();

    if (!$teacher) {
        // Si no hay profesores, usar el primer usuario disponible
        $teacher = DB::table('users')->first();
        showMessage("No se encontraron profesores, usando el usuario: {$teacher->name} (ID: {$teacher->id})", 'warning');
    } else {
        showMessage("\nProfesor encontrado: {$teacher->name} (ID: {$teacher->id})", 'success');
    }

    $totalTasksCreated = 0;
    $totalAssignments = 0;
    $errors = [];

    // Crear tareas para cada curso
    foreach ($courses as $course) {
        showMessage("\nProcesando curso: {$course->name} (ID: {$course->id})", 'info');
        
        // Obtener módulos para este curso
        $modules = DB::table('modules')
            ->where('course_id', $course->id)
            ->orderBy('order')
            ->get();
            
        if ($modules->isEmpty()) {
            $msg = "  ✗ No se encontraron módulos para el curso ID: {$course->id}";
            showMessage($msg, 'error');
            $errors[] = $msg;
            continue;
        }
        
        showMessage("  ✓ Módulos encontrados: " . $modules->count(), 'success');
        
        foreach ($tasks as $index => $taskData) {
            // Usar el módulo correspondiente al índice de la tarea (o el último si hay menos módulos)
            $moduleIndex = min($index, count($modules) - 1);
            $module = $modules[$moduleIndex];
            
            DB::beginTransaction();
            
            try {
                // Crear la tarea
                $taskId = DB::table('tasks')->insertGetId([
                    'title' => "[{$course->code}] {$taskData['title']}",
                    'description' => $taskData['description'],
                    'due_date' => $taskData['due_date'],
                    'status' => 'pending',
                    'priority' => $taskData['priority'],
                    'user_id' => $teacher->id,
                    'course_id' => $course->id,
                    'module_id' => $module->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $totalTasksCreated++;
                showMessage("  ✓ Tarea creada: {$taskData['title']} (ID: $taskId) - Módulo: {$module->title}", 'success');
                
                // Obtener estudiantes inscritos en el curso
                $students = DB::table('course_user')
                    ->where('course_id', $course->id)
                    ->pluck('user_id');
                
                $studentsCount = count($students);
                if ($studentsCount === 0) {
                    showMessage("  - Advertencia: No hay estudiantes inscritos en este curso.", 'warning');
                } else {
                    // Asignar la tarea a cada estudiante
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
                    
                    // Insertar todas las asignaciones en una sola consulta
                    DB::table('task_user')->insert($assignments);
                    $totalAssignments += $studentsCount;
                    
                    showMessage("  - Asignada a $studentsCount estudiante(s)", 'info');
                }
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                $errorMsg = "  ✗ Error al crear la tarea '{$taskData['title']}': " . $e->getMessage();
                showMessage($errorMsg, 'error');
                handleDatabaseError($e);
                $errors[] = $errorMsg;
            }
        }
    }

    // Mostrar resumen
    showMessage("\n" . str_repeat("=", 50), 'info');
    showMessage("RESUMEN DEL PROCESO", 'info');
    showMessage(str_repeat("=", 50), 'info');
    
    if ($totalTasksCreated > 0) {
        showMessage("✓ Total de tareas creadas: $totalTasksCreated", 'success');
        showMessage("✓ Total de asignaciones a estudiantes: $totalAssignments", 'success');
    } else {
        showMessage("✗ No se crearon tareas nuevas.", 'warning');
    }
    
    if (!empty($errors)) {
        showMessage("\nSe encontraron los siguientes errores:", 'error');
        foreach ($errors as $error) {
            showMessage("- $error", 'error');
        }
    }

} catch (\Exception $e) {
    showMessage("\nError: " . $e->getMessage(), 'error');
    exit(1);
}
