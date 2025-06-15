<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

// Obtener un estudiante (usando el ID 3 que vimos antes)
$student = User::find(3);

if (!$student) {
    echo "No se encontró el estudiante con ID 3\n";
    exit(1);
}

// Obtener un curso disponible
$course = Course::first();

if (!$course) {
    echo "No hay cursos disponibles en la base de datos.\n";
    exit(1);
}

// Verificar si el estudiante ya está inscrito en el curso
$existingEnrollment = Enrollment::where('user_id', $student->id)
    ->where('course_id', $course->id)
    ->first();

if ($existingEnrollment) {
    echo "El estudiante ya está inscrito en este curso.\n";
    echo "ID de la matrícula: " . $existingEnrollment->id . "\n";
    exit(0);
}

// Crear una nueva matrícula
try {
    $enrollment = new Enrollment([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'status' => 'in_progress',
        'progress' => 0,
        'enrolled_at' => now(),
    ]);
    
    $enrollment->save();
    
    echo "¡Estudiante inscrito exitosamente en el curso!\n";
    echo "ID de la matrícula: " . $enrollment->id . "\n";
    echo "Estudiante: " . $student->name . " (ID: " . $student->id . ")\n";
    echo "Curso: " . $course->title . " (ID: " . $course->id . ")\n";
    
} catch (\Exception $e) {
    echo "Error al inscribir al estudiante: " . $e->getMessage() . "\n";
    exit(1);
}
