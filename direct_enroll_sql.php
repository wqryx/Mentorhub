<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// ID del estudiante y del curso
$studentId = 3; // Estudiante Ejemplo
$courseId = 4;  // Introducción a Laravel

try {
    // 1. Verificar que el estudiante existe
    $student = DB::selectOne("SELECT id, name FROM users WHERE id = ?", [$studentId]);
    if (!$student) {
        die("Error: No se encontró el estudiante con ID $studentId\n");
    }
    
    echo "Estudiante: {$student->name} (ID: {$student->id})\n";
    
    // 2. Verificar que el curso existe
    $course = DB::selectOne("SELECT id, name, code FROM courses WHERE id = ?", [$courseId]);
    if (!$course) {
        die("Error: No se encontró el curso con ID $courseId\n");
    }
    
    echo "Curso: {$course->name} (Código: {$course->code}, ID: {$course->id})\n\n";
    
    // 3. Verificar si ya está inscrito
    $existing = DB::selectOne(
        "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?", 
        [$studentId, $courseId]
    );
    
    if ($existing) {
        die("El estudiante ya está inscrito en este curso. ID de matrícula: {$existing->id}\n");
    }
    
    // 4. Insertar la matrícula directamente con SQL
    echo "Insertando matrícula...\n";
    
    $result = DB::insert(
        "INSERT INTO enrollments (user_id, course_id, status, progress, enrolled_at, created_at, updated_at) " .
        "VALUES (?, ?, 'in_progress', 0, NOW(), NOW(), NOW())",
        [$studentId, $courseId]
    );
    
    if ($result) {
        $enrollmentId = DB::getPdo()->lastInsertId();
        echo "¡Matrícula creada exitosamente! ID: $enrollmentId\n";
    } else {
        echo "No se pudo crear la matrícula.\n";
    }
    
} catch (\Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error
    $errorInfo = DB::connection()->getPdo()->errorInfo();
    if (!empty($errorInfo)) {
        echo "\nDetalles del error SQL:\n";
        echo "- Código: " . ($errorInfo[1] ?? 'N/A') . "\n";
        echo "- Mensaje: " . ($errorInfo[2] ?? 'N/A') . "\n";
    }
}

// Mostrar todas las matrículas del estudiante
echo "\n=== MATRÍCULAS DEL ESTUDIANTE ===\n";

$enrollments = DB::select(
    "SELECT e.*, c.name as course_name, c.code as course_code " .
    "FROM enrollments e " .
    "LEFT JOIN courses c ON e.course_id = c.id " .
    "WHERE e.user_id = ?", 
    [$studentId]
);

if (empty($enrollments)) {
    echo "El estudiante no tiene matrículas.\n";
} else {
    foreach ($enrollments as $e) {
        echo "- Matrícula #{$e->id}: " . 
             "Curso: " . ($e->course_name ?? "[Curso no encontrado, ID: {$e->course_id}]") . 
             " (Código: " . ($e->course_code ?? 'N/A') . 
             "), Estado: {$e->status}, " . 
             "Progreso: {$e->progress}%, " .
             "Inscrito el: " . ($e->enrolled_at ?? 'N/A') . "\n";
    }
}
