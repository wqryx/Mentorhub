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
    // Verificar si la tabla modules existe
    if (!Schema::hasTable('modules')) {
        throw new Exception("La tabla 'modules' no existe en la base de datos.");
    }

    // Verificar si ya hay módulos en la base de datos
    $existingModules = DB::table('modules')->count();
    if ($existingModules > 0) {
        showMessage("Ya existen $existingModules módulos en la base de datos. No se crearán módulos de ejemplo.", 'warning');
        exit;
    }

    // Obtener los cursos disponibles
    $courses = DB::table('courses')->get(['id', 'name', 'code']);
    if ($courses->isEmpty()) {
        throw new Exception('No hay cursos disponibles para asignar módulos.');
    }

    showMessage("Cursos disponibles para asignar módulos:", 'info');
    foreach ($courses as $course) {
        showMessage("- {$course->name} (ID: {$course->id}, Código: {$course->code})", 'info');
    }

    // Obtener el ID del profesor (usuario con rol de mentor o admin)
    $teacherId = DB::table('model_has_roles')
        ->whereIn('role_id', [1, 2]) // IDs de admin y mentor
        ->value('model_id');

    if (!$teacherId) {
        // Si no hay profesores, usar el primer usuario disponible
        $teacherId = DB::table('users')->value('id');
        showMessage("No se encontró un profesor, usando el usuario con ID: $teacherId", 'warning');
    } else {
        showMessage("Usuario profesor encontrado con ID: $teacherId", 'success');
    }

    // Módulos de ejemplo para cada curso
    $modulesByCourse = [
        'LAR-101' => [
            ['name' => 'Introducción a Laravel', 'description' => 'Conceptos básicos de Laravel', 'order' => 1],
            ['name' => 'Rutas y Controladores', 'description' => 'Manejo de rutas y controladores en Laravel', 'order' => 2],
            ['name' => 'Vistas y Blade', 'description' => 'Uso de plantillas Blade', 'order' => 3],
        ],
        'VUE-201' => [
            ['name' => 'Introducción a Vue.js', 'description' => 'Conceptos básicos de Vue.js', 'order' => 1],
            ['name' => 'Componentes', 'description' => 'Creación y uso de componentes', 'order' => 2],
            ['name' => 'Vuex', 'description' => 'Gestión de estado con Vuex', 'order' => 3],
        ],
        'SQL-101' => [
            ['name' => 'Introducción a SQL', 'description' => 'Conceptos básicos de bases de datos', 'order' => 1],
            ['name' => 'Consultas SQL', 'description' => 'Consultas SELECT, INSERT, UPDATE, DELETE', 'order' => 2],
            ['name' => 'Diseño de Bases de Datos', 'description' => 'Normalización y relaciones', 'order' => 3],
        ]
    ];

    $totalModulesCreated = 0;
    $now = now();

    // Crear módulos para cada curso
    foreach ($courses as $course) {
        $courseCode = $course->code;
        
        if (!isset($modulesByCourse[$courseCode])) {
            showMessage("\nNo hay módulos definidos para el curso con código: $courseCode", 'warning');
            continue;
        }

        showMessage("\nCreando módulos para el curso: {$course->name} (ID: {$course->id})", 'info');
        
        $modulesToInsert = [];
        
        foreach ($modulesByCourse[$courseCode] as $moduleData) {
            $modulesToInsert[] = [
                'title' => $moduleData['name'], // Usar 'title' en lugar de 'name'
                'description' => $moduleData['description'],
                'order' => $moduleData['order'],
                'course_id' => $course->id,
                'status' => 'active',
                'is_free' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            
            showMessage("  - Módulo: {$moduleData['name']} (Orden: {$moduleData['order']})", 'info');
        }
        
        // Insertar todos los módulos del curso en una sola consulta
        if (!empty($modulesToInsert)) {
            DB::table('modules')->insert($modulesToInsert);
            $totalModulesCreated += count($modulesToInsert);
            showMessage("  ✓ Módulos creados: " . count($modulesToInsert), 'success');
        }
    }

    showMessage("\n¡Proceso completado con éxito!", 'success');
    showMessage("- Total de módulos creados: $totalModulesCreated", 'info');

} catch (\Exception $e) {
    showMessage("\nError: " . $e->getMessage(), 'error');
    exit(1);
}

echo "\n";
