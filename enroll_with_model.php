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

try {
    // 1. Verificar que el estudiante existe
    $student = User::find($studentId);
    if (!$student) {
        die("Error: No se encontró el estudiante con ID $studentId\n");
    }
    
    echo "Estudiante: {$student->name} (ID: {$student->id})\n";
    
    // 2. Verificar que el curso existe
    $course = Course::find($courseId);
    if (!$course) {
        die("Error: No se encontró el curso con ID $courseId\n");
    }
    
    echo "Curso: {$course->name} (Código: {$course->code}, ID: {$course->id})\n\n";
    
    // 3. Verificar si ya está inscrito
    $existingEnrollment = Enrollment::where('user_id', $studentId)
        ->where('course_id', $courseId)
        ->first();
    
    if ($existingEnrollment) {
        die("El estudiante ya está inscrito en este curso. ID de matrícula: {$existingEnrollment->id}\n");
    }
    
    // 4. Crear la matrícula usando el modelo
    $enrollment = new Enrollment([
        'user_id' => $studentId,
        'course_id' => $courseId,
        'status' => 'in_progress',
        'enrollment_date' => now(),
        'completion_date' => null,
    ]);
    
    $enrollment->save();
    
    echo "¡Matrícula creada exitosamente!\n";
    echo "ID de la matrícula: {$enrollment->id}\n";
    
} catch (\Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        echo "Error detallado: " . $e->getPrevious()->getMessage() . "\n";
    }
}

// 5. Mostrar todas las matrículas del estudiante
try {
    echo "\n=== MATRÍCULAS DEL ESTUDIANTE ===\n";
    
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
