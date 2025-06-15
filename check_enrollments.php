<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar inscripciones del usuario con ID 3
echo "Verificando inscripciones del usuario con ID 3...\n";

// Verificar si la tabla course_user existe
if (!Schema::hasTable('course_user')) {
    echo "La tabla course_user no existe.\n";
    exit;
}

// Obtener las inscripciones del usuario 3
$enrollments = DB::table('course_user')
    ->where('user_id', 3)
    ->get();

echo "Inscripciones encontradas: " . $enrollments->count() . "\n";

if ($enrollments->isNotEmpty()) {
    echo "\nDetalles de las inscripciones:\n";
    foreach ($enrollments as $enrollment) {
        echo "- Curso ID: " . $enrollment->course_id . "\n";
        
        // Obtener información del curso
        $course = DB::table('courses')
            ->where('id', $enrollment->course_id)
            ->first();
            
        if ($course) {
            echo "  Nombre del curso: " . ($course->name ?? 'N/A') . "\n";
            echo "  Código: " . ($course->code ?? 'N/A') . "\n";
        } else {
            echo "  No se pudo encontrar información del curso.\n";
        }
        
        echo "  Fecha de inscripción: " . $enrollment->created_at . "\n";
        echo "  Progreso: " . ($enrollment->progress ?? 'N/A') . "%\n";
        echo "  Completado: " . ($enrollment->completed ? 'Sí' : 'No') . "\n";
        echo "\n";
    }
} else {
    echo "No se encontraron inscripciones para el usuario con ID 3.\n";
}
