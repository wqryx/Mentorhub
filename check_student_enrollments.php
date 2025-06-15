<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// ID del estudiante
$studentId = 3;

// Obtener las matrículas del estudiante con información de los cursos
try {
    echo "=== MATRÍCULAS DEL ESTUDIANTE (ID: $studentId) ===\n\n";
    
    $enrollments = DB::table('enrollments as e')
        ->join('courses as c', 'e.course_id', '=', 'c.id')
        ->where('e.user_id', $studentId)
        ->select('e.id as enrollment_id', 'e.status', 'e.progress', 'e.enrolled_at', 
                 'c.id as course_id', 'c.name as course_name', 'c.code as course_code')
        ->get();
    
    if ($enrollments->isEmpty()) {
        echo "El estudiante no está inscrito en ningún curso.\n";
    } else {
        foreach ($enrollments as $enrollment) {
            echo "- Matrícula #{$enrollment->enrollment_id}:\n";
            echo "  Curso: {$enrollment->course_name} (Código: {$enrollment->course_code}, ID: {$enrollment->course_id})\n";
            echo "  Estado: {$enrollment->status}\n";
            echo "  Progreso: {$enrollment->progress}%\n";
            echo "  Fecha de inscripción: {$enrollment->enrolled_at}\n\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error al obtener las matrículas: " . $e->getMessage() . "\n";
}
