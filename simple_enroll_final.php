<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Obtener el ID del estudiante
    $studentId = 3; // ID del estudiante que vimos antes
    
    // Obtener el primer curso activo
    $course = DB::table('courses')
        ->where('is_active', 1)
        ->first(['id', 'name']);
    
    if (!$course) {
        echo "No hay cursos activos disponibles.\n";
        exit(1);
    }
    
    echo "Inscribiendo al estudiante ID $studentId en el curso: " . $course->name . " (ID: " . $course->id . ")\n";
    
    // Verificar si ya existe la matrícula
    $exists = DB::table('enrollments')
        ->where('user_id', $studentId)
        ->where('course_id', $course->id)
        ->exists();
    
    if ($exists) {
        echo "El estudiante ya está inscrito en este curso.\n";
        exit(0);
    }
    
    // Insertar la matrícula
    $enrollmentId = DB::table('enrollments')->insertGetId([
        'user_id' => $studentId,
        'course_id' => $course->id,
        'status' => 'in_progress',
        'progress' => 0,
        'enrolled_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "¡Matrícula creada exitosamente! ID: $enrollmentId\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        echo "Error detallado: " . $e->getPrevious()->getMessage() . "\n";
    }
    
    // Mostrar información de la consulta SQL si está disponible
    if (method_exists($e, 'getSql')) {
        echo "Consulta SQL: " . $e->getSql() . "\n";
        if (method_exists($e, 'getBindings')) {
            echo "Parámetros: " . print_r($e->getBindings(), true) . "\n";
        }
    }
}
