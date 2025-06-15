<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function enrollStudent($studentId, $courseId) {
    try {
        // Verificar si el estudiante existe
        $student = DB::table('users')->find($studentId);
        if (!$student) {
            return ["success" => false, "message" => "El estudiante con ID $studentId no existe."];
        }
        
        // Verificar si el curso existe
        $course = DB::table('courses')->find($courseId);
        if (!$course) {
            return ["success" => false, "message" => "El curso con ID $courseId no existe."];
        }
        
        // Verificar si ya está inscrito
        $existing = DB::table('enrollments')
            ->where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->first();
            
        if ($existing) {
            return ["success" => false, "message" => "El estudiante ya está inscrito en este curso.", "enrollment_id" => $existing->id];
        }
        
        // Intentar insertar la matrícula
        $enrollmentId = DB::table('enrollments')->insertGetId([
            'user_id' => $studentId,
            'course_id' => $courseId,
            'status' => 'in_progress',
            'progress' => 0,
            'enrolled_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return [
            "success" => true, 
            "message" => "Estudiante inscrito exitosamente.",
            "enrollment_id" => $enrollmentId
        ];
        
    } catch (\Exception $e) {
        return [
            "success" => false, 
            "message" => "Error al inscribir al estudiante: " . $e->getMessage(),
            "error_details" => [
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "trace" => $e->getTraceAsString()
            ]
        ];
    }
}

// ID del estudiante y curso a inscribir
$studentId = 3; // Estudiante Ejemplo
$courseId = 4;  // Introducción a Laravel

// Realizar la inscripción
$result = enrollStudent($studentId, $courseId);

// Mostrar resultados
echo "=== Resultado de la inscripción ===\n";
echo "Éxito: " . ($result['success'] ? 'Sí' : 'No') . "\n";
echo "Mensaje: " . $result['message'] . "\n";

if (isset($result['enrollment_id'])) {
    echo "ID de la matrícula: " . $result['enrollment_id'] . "\n";
}

if (isset($result['error_details'])) {
    echo "\nDetalles del error:\n";
    echo "Archivo: " . $result['error_details']['file'] . "\n";
    echo "Línea: " . $result['error_details']['line'] . "\n";
    echo "Traza: " . $result['error_details']['trace'] . "\n";
}

echo "\n";
