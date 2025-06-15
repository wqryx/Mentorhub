<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// ID del estudiante a inscribir
$studentId = 3;

// Verificar si el usuario existe y es estudiante
$student = DB::table('users')
    ->join('model_has_roles', function($join) {
        $join->on('users.id', '=', 'model_has_roles.model_id')
             ->where('model_has_roles.role_id', 3); // ID del rol 'student'
    })
    ->where('users.id', $studentId)
    ->first();

if (!$student) {
    echo "El usuario con ID $studentId no existe o no tiene el rol de estudiante.\n";
    exit;
}

echo "Inscribiendo al estudiante: {$student->name} (ID: $studentId)\n\n";

// Obtener todos los cursos activos
$courses = DB::table('courses')
    ->where('is_active', true)
    ->get(['id', 'name', 'code']);

if ($courses->isEmpty()) {
    echo "No hay cursos activos disponibles para inscripción.\n";
    exit;
}

echo "Cursos disponibles para inscripción:\n";
echo "--------------------------------\n";

foreach ($courses as $index => $course) {
    echo ($index + 1) . ". {$course->name} ({$course->code}) [ID: {$course->id}]\n";
}

echo "\n";

// Inscribir al estudiante en los cursos (por ejemplo, en los dos primeros)
$coursesToEnroll = $courses->take(2);

foreach ($coursesToEnroll as $course) {
    // Verificar si ya está inscrito
    $alreadyEnrolled = DB::table('course_user')
        ->where('user_id', $studentId)
        ->where('course_id', $course->id)
        ->exists();
    
    if ($alreadyEnrolled) {
        echo "El estudiante ya está inscrito en el curso: {$course->name}\n";
        continue;
    }
    
    // Inscribir al estudiante en el curso
    DB::table('course_user')->insert([
        'user_id' => $studentId,
        'course_id' => $course->id,
        'enrolled_at' => now(),
        'progress' => 0,
        'completed' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "Inscrito exitosamente en: {$course->name} (ID: {$course->id})\n";
}

echo "\nProceso de inscripción completado.\n";
