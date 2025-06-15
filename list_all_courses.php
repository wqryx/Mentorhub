<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== LISTA DE TODOS LOS CURSOS ===\n\n";
    
    // Obtener todos los cursos
    $courses = DB::table('courses')
        ->select('id', 'name', 'code', 'is_active', 'created_at', 'updated_at')
        ->get();
    
    if ($courses->isEmpty()) {
        echo "No hay cursos en la base de datos.\n";
        exit(0);
    }
    
    foreach ($courses as $course) {
        echo "ID: {$course->id}\n";
        echo "- Nombre: {$course->name}\n";
        echo "- Código: {$course->code}\n";
        echo "- Activo: " . ($course->is_active ? 'Sí' : 'No') . "\n";
        echo "- Creado: {$course->created_at}\n";
        echo "- Actualizado: {$course->updated_at}\n";
        echo "\n";
    }
    
    // Verificar matrículas del estudiante
    $studentId = 3;
    echo "\n=== MATRÍCULAS DEL ESTUDIANTE (ID: $studentId) ===\n\n";
    
    $enrollments = DB::table('enrollments as e')
        ->leftJoin('courses as c', 'e.course_id', '=', 'c.id')
        ->where('e.user_id', $studentId)
        ->select('e.*', 'c.name as course_name', 'c.code as course_code')
        ->get();
    
    if ($enrollments->isEmpty()) {
        echo "El estudiante no tiene matrículas.\n";
    } else {
        echo "El estudiante tiene " . $enrollments->count() . " matrículas:\n\n";
        
        foreach ($enrollments as $enrollment) {
            echo "Matrícula ID: {$enrollment->id}\n";
            echo "- Curso ID: {$enrollment->course_id}";
            
            if (isset($enrollment->course_name)) {
                echo " ({$enrollment->course_name} - {$enrollment->course_code})\n";
            } else {
                echo " (¡Curso no encontrado!)";
                
                // Verificar si el curso existe
                $courseExists = DB::table('courses')->where('id', $enrollment->course_id)->exists();
                echo $courseExists ? " (El curso existe pero hay un problema con la consulta)" : " (El curso no existe en la base de datos)";
                echo "\n";
                
                // Sugerir eliminar la matrícula si el curso no existe
                if (!$courseExists) {
                    echo "  ¡RECOMENDACIÓN: Esta matrícula hace referencia a un curso que no existe. " .
                         "Puedes eliminarla con: DELETE FROM enrollments WHERE id = {$enrollment->id};\n";
                }
            }
            
            echo "- Estado: {$enrollment->status}\n";
            echo "- Progreso: {$enrollment->progress}%\n";
            echo "- Fecha de inscripción: " . ($enrollment->enrolled_at ?? 'No definida') . "\n";
            echo "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
}
