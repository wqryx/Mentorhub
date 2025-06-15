<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

// Obtener el estudiante con ID 3
$student = User::find(3);

if (!$student) {
    echo "No se encontró el estudiante con ID 3.\n";
    exit(1);
}

echo "Estudiante: " . $student->name . " (ID: " . $student->id . ")\n\n";

// Mostrar los cursos disponibles
echo "Cursos disponibles:\n";
$courses = Course::all(['id', 'name', 'description', 'is_active']);

if ($courses->isEmpty()) {
    echo "No hay cursos disponibles.\n";
    exit(1);
}

foreach ($courses as $course) {
    echo "- ID: " . $course->id . ", Nombre: " . $course->name . 
         ", Activo: " . ($course->is_active ? 'Sí' : 'No') . "\n";
}

echo "\n";

// Seleccionar el primer curso activo
$course = $courses->first(function ($course) {
    return $course->is_active == 1;
});

if (!$course) {
    echo "No hay cursos activos disponibles.\n";
    exit(1);
}

echo "Inscribiendo al estudiante en el curso: " . $course->name . " (ID: " . $course->id . ")\n";

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
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    $enrollment->save();
    
    echo "¡Estudiante inscrito exitosamente en el curso!\n";
    echo "ID de la matrícula: " . $enrollment->id . "\n";
    
} catch (\Exception $e) {
    echo "Error al inscribir al estudiante: " . $e->getMessage() . "\n";
    if ($e->getPrevious()) {
        echo "Error detallado: " . $e->getPrevious()->getMessage() . "\n";
    }
    // Mostrar la consulta SQL que falló
    if (method_exists($e, 'getSql')) {
        echo "Consulta SQL: " . $e->getSql() . "\n";
        echo "Bindings: " . print_r($e->getBindings(), true) . "\n";
    }
    exit(1);
}
