<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// ID del estudiante
$studentId = 3;

// Verificación directa de matrículas
try {
    echo "=== VERIFICACIÓN DIRECTA DE MATRÍCULAS ===\n\n";
    
    // 1. Verificar si el estudiante existe
    $student = DB::table('users')->find($studentId);
    if (!$student) {
        die("Error: No se encontró el estudiante con ID $studentId.\n");
    }
    
    echo "Estudiante: {$student->name} (ID: {$student->id}, Email: {$student->email})\n\n";
    
    // 2. Contar matrículas totales
    $totalEnrollments = DB::table('enrollments')->where('user_id', $studentId)->count();
    echo "Total de matrículas registradas: $totalEnrollments\n";
    
    // 3. Verificar si hay matrículas con datos inconsistentes
    echo "\nVerificando consistencia de datos...\n";
    
    // a) Matrículas sin curso existente
    $orphanedEnrollments = DB::table('enrollments as e')
        ->leftJoin('courses as c', 'e.course_id', '=', 'c.id')
        ->where('e.user_id', $studentId)
        ->whereNull('c.id')
        ->select('e.*')
        ->get();
    
    echo "- Matrículas con cursos que no existen: " . $orphanedEnrollments->count() . "\n";
    
    // b) Matrículas con cursos existentes
    $validEnrollments = DB::table('enrollments as e')
        ->join('courses as c', 'e.course_id', '=', 'c.id')
        ->where('e.user_id', $studentId)
        ->select('e.id', 'e.course_id', 'c.name as course_name', 'e.status', 'e.progress', 'e.enrolled_at')
        ->get();
    
    echo "- Matrículas con cursos existentes: " . $validEnrollments->count() . "\n";
    
    if ($validEnrollments->isNotEmpty()) {
        echo "\nDetalles de las matrículas válidas:\n";
        foreach ($validEnrollments as $enrollment) {
            echo "  - Matrícula #{$enrollment->id}: Curso '{$enrollment->course_name}' (ID: {$enrollment->course_id}), " .
                 "Estado: {$enrollment->status}, Progreso: {$enrollment->progress}%, " .
                 "Inscrito el: {$enrollment->enrolled_at}\n";
        }
    }
    
    // c) Verificar si hay cursos disponibles para inscribir
    $availableCourses = DB::table('courses as c')
        ->whereNotExists(function ($query) use ($studentId) {
            $query->select(DB::raw(1))
                  ->from('enrollments as e')
                  ->whereRaw('e.course_id = c.id')
                  ->where('e.user_id', $studentId);
        })
        ->where('c.is_active', 1)
        ->select('c.id', 'c.name', 'c.code', 'c.description')
        ->get();
    
    echo "\nCursos disponibles para inscripción: " . $availableCourses->count() . "\n";
    
    if ($availableCourses->isNotEmpty()) {
        echo "\nPuedes inscribir al estudiante en los siguientes cursos:\n";
        foreach ($availableCourses as $course) {
            echo "- ID: {$course->id}, Código: {$course->code}, Nombre: {$course->name}\n";
        }
        
        // Si hay cursos disponibles, ofrecer inscribir al estudiante en el primero
        $courseToEnroll = $availableCourses->first();
        echo "\nPara inscribir al estudiante en el curso '{$courseToEnroll->name}', ejecuta:\n";
        echo "php artisan tinker --execute=\"\\DB::table('enrollments')->insert(['user_id' => $studentId, 'course_id' => {$courseToEnroll->id}, 'status' => 'in_progress', 'progress' => 0, 'enrolled_at' => now(), 'created_at' => now(), 'updated_at' => now()]);\"\n";
    }
    
} catch (\Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error SQL si está disponible
    if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
        $errorInfo = DB::connection()->getPdo()->errorInfo();
        if (!empty($errorInfo)) {
            echo "\nDetalles del error SQL:\n";
            echo "- Código: " . ($errorInfo[1] ?? 'N/A') . "\n";
            echo "- Mensaje: " . ($errorInfo[2] ?? 'N/A') . "\n";
        }
    }
}
