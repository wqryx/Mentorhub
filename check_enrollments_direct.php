<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function printHeader($title) {
    echo "\n=== " . strtoupper($title) . " ===\n\n";
}

try {
    // ID del estudiante
    $studentId = 3;
    
    // 1. Verificar que el estudiante existe
    $student = DB::table('users')->find($studentId);
    if (!$student) {
        die("✗ Error: No se encontró el estudiante con ID $studentId\n");
    }
    
    printHeader("INFORMACIÓN DEL ESTUDIANTE");
    echo "ID: {$student->id}\n";
    echo "Nombre: {$student->name}\n";
    echo "Email: {$student->email}\n";
    
    // 2. Obtener todas las matrículas del estudiante
    printHeader("MATRÍCULAS DEL ESTUDIANTE");
    
    $enrollments = DB::table('enrollments as e')
        ->leftJoin('courses as c', 'e.course_id', '=', 'c.id')
        ->where('e.user_id', $studentId)
        ->select('e.*', 'c.name as course_name', 'c.code as course_code')
        ->get();
    
    if ($enrollments->isEmpty()) {
        echo "El estudiante no tiene matrículas.\n";
    } else {
        echo "Total de matrículas: " . $enrollments->count() . "\n\n";
        
        foreach ($enrollments as $enrollment) {
            echo "- Matrícula #{$enrollment->id}:\n";
            echo "  Curso: " . ($enrollment->course_name ?: "[Curso no encontrado, ID: {$enrollment->course_id}]") . "\n";
            echo "  Código: " . ($enrollment->course_code ?: 'N/A') . "\n";
            echo "  Estado: " . ($enrollment->status ?? 'No definido') . "\n";
            echo "  Fecha de inscripción: " . ($enrollment->enrollment_date ?? 'No definida') . "\n";
            echo "  Creado: " . ($enrollment->created_at ?? 'N/A') . "\n";
            echo "  Actualizado: " . ($enrollment->updated_at ?? 'N/A') . "\n\n";
        }
    }
    
    // 3. Mostrar cursos disponibles
    printHeader("CURSOS DISPONIBLES");
    
    $courses = DB::table('courses')
        ->where('is_active', 1)
        ->select('id', 'name', 'code', 'description')
        ->get();
    
    if ($courses->isEmpty()) {
        echo "No hay cursos activos disponibles.\n";
    } else {
        foreach ($courses as $course) {
            echo "- ID: {$course->id}, Código: {$course->code}, Nombre: {$course->name}\n";
            echo "  Descripción: " . substr($course->description ?? 'Sin descripción', 0, 100) . "...\n\n";
        }
    }
    
} catch (\Exception $e) {
    printHeader("ERROR");
    echo "✗ Error: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        echo "Error detallado: " . $e->getPrevious()->getMessage() . "\n";
    }
    
    // Mostrar información de depuración adicional
    $errorInfo = DB::connection()->getPdo()->errorInfo();
    if (!empty($errorInfo)) {
        echo "\nInformación de error de PDO:\n";
        print_r($errorInfo);
    }
}
