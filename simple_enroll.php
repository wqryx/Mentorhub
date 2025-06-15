<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

// Obtener el primer estudiante con rol 'student'
$student = User::role('student')->first();

if (!$student) {
    echo "No se encontró ningún estudiante en la base de datos.\n";
    exit(1);
}

echo "Estudiante encontrado:\n";
echo "- ID: " . $student->id . "\n";
echo "- Nombre: " . $student->name . "\n";
echo "- Email: " . $student->email . "\n\n";

// Obtener el primer curso activo
$course = Course::where('status', 'published')->orWhere('status', 'active')->first();

if (!$course) {
    echo "No se encontró ningún curso activo en la base de datos.\n";
    exit(1);
}

echo "Curso encontrado:\n";
echo "- ID: " . $course->id . "\n";
echo "- Título: " . $course->title . "\n";
echo "- Estado: " . $course->status . "\n\n";

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
    $enrollment = new Enrollment();
    $enrollment->user_id = $student->id;
    $enrollment->course_id = $course->id;
    $enrollment->status = 'in_progress';
    $enrollment->progress = 0;
    $enrollment->enrolled_at = now();
    
    $enrollment->save();
    
    echo "¡Estudiante inscrito exitosamente en el curso!\n";
    echo "ID de la matrícula: " . $enrollment->id . "\n";
    
} catch (\Exception $e) {
    echo "Error al inscribir al estudiante: " . $e->getMessage() . "\n";
    if (str_contains($e->getMessage(), 'SQLSTATE')) {
        echo "Error de SQL: " . $e->getPrevious()->getMessage() . "\n";
    }
    exit(1);
}
