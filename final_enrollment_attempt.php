<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;

// ID del estudiante y del curso
$studentId = 3; // Estudiante Ejemplo
$courseId = 4;  // Introducción a Laravel

function printHeader($title) {
    echo "\n=== " . strtoupper($title) . " ===\n\n";
}

try {
    // 1. Verificar que el estudiante existe
    $student = User::find($studentId);
    if (!$student) {
        die("✗ Error: No se encontró el estudiante con ID $studentId\n");
    }
    
    printHeader("INFORMACIÓN DEL ESTUDIANTE");
    echo "ID: {$student->id}\n";
    echo "Nombre: {$student->name}\n";
    echo "Email: {$student->email}\n";
    
    // 2. Verificar que el curso existe
    $course = Course::find($courseId);
    if (!$course) {
        die("✗ Error: No se encontró el curso con ID $courseId\n");
    }
    
    printHeader("INFORMACIÓN DEL CURSO");
    echo "ID: {$course->id}\n";
    echo "Nombre: {$course->name}\n";
    echo "Código: {$course->code}\n";
    echo "Activo: " . ($course->is_active ? 'Sí' : 'No') . "\n";
    
    // 3. Verificar si ya está inscrito
    $existingEnrollment = Enrollment::where('user_id', $studentId)
        ->where('course_id', $courseId)
        ->first();
    
    if ($existingEnrollment) {
        printHeader("MATRÍCULA EXISTENTE");
        echo "El estudiante ya está inscrito en este curso.\n";
        echo "ID de la matrícula: {$existingEnrollment->id}\n";
        echo "Estado: {$existingEnrollment->status}\n";
        echo "Fecha de inscripción: " . ($existingEnrollment->enrollment_date ? $existingEnrollment->enrollment_date->format('Y-m-d') : 'N/A') . "\n";
        exit(0);
    }
    
    // 4. Crear la matrícula usando el modelo con un estado válido
    printHeader("CREANDO NUEVA MATRÍCULA");
    
    $enrollment = new Enrollment([
        'user_id' => $studentId,
        'course_id' => $courseId,
        'status' => 'active',  // Usando un valor válido confirmado
        'enrollment_date' => now(),
        'completion_date' => null,
    ]);
    
    $enrollment->save();
    
    echo "✓ ¡Matrícula creada exitosamente!\n";
    echo "ID de la matrícula: {$enrollment->id}\n";
    
} catch (\Exception $e) {
    printHeader("ERROR");
    echo "✗ Error: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        echo "Error detallado: " . $e->getPrevious()->getMessage() . "\n";
    }
}

// 5. Mostrar todas las matrículas del estudiante
try {
    printHeader("MATRÍCULAS ACTUALES DEL ESTUDIANTE");
    
    $enrollments = Enrollment::with('course')
        ->where('user_id', $studentId)
        ->get();
    
    if ($enrollments->isEmpty()) {
        echo "El estudiante no tiene matrículas.\n";
    } else {
        foreach ($enrollments as $enrollment) {
            echo "- Matrícula #{$enrollment->id}: ";
            echo "Curso: " . ($enrollment->course ? $enrollment->course->name : "[Curso no encontrado, ID: {$enrollment->course_id}]") . "; ";
            echo "Estado: {$enrollment->status}; ";
            echo "Fecha de inscripción: " . ($enrollment->enrollment_date ? $enrollment->enrollment_date->format('Y-m-d') : 'N/A') . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error al obtener las matrículas: " . $e->getMessage() . "\n";
}

echo "\n";
