<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function cleanOrphanedEnrollments($studentId) {
    echo "=== LIMPIANDO MATRÍCULAS HUÉRFANAS ===\n";
    
    // Encontrar matrículas que hacen referencia a cursos que no existen
    $orphanedEnrollments = DB::table('enrollments as e')
        ->leftJoin('courses as c', 'e.course_id', '=', 'c.id')
        ->where('e.user_id', $studentId)
        ->whereNull('c.id')
        ->select('e.id', 'e.course_id')
        ->get();
    
    if ($orphanedEnrollments->isEmpty()) {
        echo "No se encontraron matrículas huérfanas.\n\n";
        return 0;
    }
    
    echo "Se encontraron " . $orphanedEnrollments->count() . " matrículas huérfanas.\n";
    
    foreach ($orphanedEnrollments as $enrollment) {
        echo "- Eliminando matrícula ID: {$enrollment->id} (Curso ID: {$enrollment->course_id} no existe)\n";
    }
    
    // Eliminar las matrículas huérfanas
    $deleted = DB::table('enrollments as e')
        ->leftJoin('courses as c', 'e.course_id', '=', 'c.id')
        ->where('e.user_id', $studentId)
        ->whereNull('c.id')
        ->delete();
    
    echo "Se eliminaron $deleted matrículas huérfanas.\n\n";
    return $deleted;
}

function enrollStudentInCourse($studentId, $courseId) {
    echo "=== INSCRIBIENDO ESTUDIANTE EN CURSO ===\n";
    
    // Verificar si el estudiante existe
    $student = DB::table('users')->find($studentId);
    if (!$student) {
        echo "✗ No se encontró el estudiante con ID $studentId.\n\n";
        return false;
    }
    
    echo "Estudiante: {$student->name} (ID: {$student->id})\n";
    
    // Verificar si el curso existe
    $course = DB::table('courses')->find($courseId);
    if (!$course) {
        echo "✗ No se encontró el curso con ID $courseId.\n\n";
        return false;
    }
    
    echo "Curso: {$course->name} (ID: {$course->id}, Código: {$course->code})\n";
    
    // Verificar si ya está inscrito
    $existing = DB::table('enrollments')
        ->where('user_id', $studentId)
        ->where('course_id', $courseId)
        ->exists();
    
    if ($existing) {
        echo "ℹ️ El estudiante ya está inscrito en este curso.\n\n";
        return true;
    }
    
    // Crear la matrícula
    try {
        $enrollmentId = DB::table('enrollments')->insertGetId([
            'user_id' => $studentId,
            'course_id' => $courseId,
            'status' => 'in_progress',
            'progress' => 0,
            'enrolled_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✓ ¡Matrícula creada exitosamente! ID: $enrollmentId\n\n";
        return true;
        
    } catch (\Exception $e) {
        echo "✗ Error al crear la matrícula: " . $e->getMessage() . "\n\n";
        return false;
    }
}

// ID del estudiante
$studentId = 3;

// 1. Limpiar matrículas huérfanas
$cleaned = cleanOrphanedEnrollments($studentId);

// 2. Obtener el primer curso activo disponible
$course = DB::table('courses')
    ->where('is_active', 1)
    ->orderBy('id')
    ->first();

if (!$course) {
    die("No hay cursos activos disponibles.\n");
}

// 3. Inscribir al estudiante en el curso
enrollStudentInCourse($studentId, $course->id);

// 4. Mostrar las matrículas actuales del estudiante
echo "=== MATRÍCULAS ACTUALES DEL ESTUDIANTE ===\n";

$enrollments = DB::table('enrollments as e')
    ->join('courses as c', 'e.course_id', '=', 'c.id')
    ->where('e.user_id', $studentId)
    ->select('e.id', 'e.course_id', 'c.name as course_name', 'e.status', 'e.progress', 'e.enrolled_at')
    ->get();

if ($enrollments->isEmpty()) {
    echo "El estudiante no tiene matrículas activas.\n";
} else {
    foreach ($enrollments as $enrollment) {
        echo "- Matrícula #{$enrollment->id}: " . 
             "Curso '{$enrollment->course_name}' (ID: {$enrollment->course_id}), " . 
             "Estado: {$enrollment->status}, " . 
             "Progreso: {$enrollment->progress}%, " .
             "Inscrito el: {$enrollment->enrolled_at}\n";
    }
}
