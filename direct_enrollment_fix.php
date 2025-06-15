<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Configuración
$studentId = 3; // ID del estudiante
$courseId = 4;  // ID del curso (Introducción a Laravel)

// Verificar la conexión a la base de datos
try {
    echo "=== VERIFICACIÓN DE CONEXIÓN A LA BASE DE DATOS ===\n";
    DB::connection()->getPdo();
    echo "✓ Conexión exitosa a la base de datos.\n\n";
} catch (\Exception $e) {
    die("✗ Error de conexión: " . $e->getMessage() . "\n");
}

// Verificar si el estudiante existe
$student = DB::table('users')->find($studentId);
if (!$student) {
    die("✗ No se encontró el estudiante con ID $studentId.\n");
}
echo "=== ESTUDIANTE ===\n";
echo "ID: {$student->id}\n";
echo "Nombre: {$student->name}\n";
echo "Email: {$student->email}\n\n";

// Verificar si el curso existe
$course = DB::table('courses')->find($courseId);
if (!$course) {
    die("✗ No se encontró el curso con ID $courseId.\n");
}
echo "=== CURSO ===\n";
echo "ID: {$course->id}\n";
echo "Nombre: {$course->name}\n";
echo "Código: {$course->code}\n";
echo "Activo: " . ($course->is_active ? 'Sí' : 'No') . "\n\n";

// Verificar si ya existe la matrícula
$existingEnrollment = DB::table('enrollments')
    ->where('user_id', $studentId)
    ->where('course_id', $courseId)
    ->first();

if ($existingEnrollment) {
    echo "=== MATRÍCULA EXISTENTE ===\n";
    echo "El estudiante ya está inscrito en este curso.\n";
    echo "ID de la matrícula: {$existingEnrollment->id}\n";
    echo "Estado: {$existingEnrollment->status}\n";
    echo "Progreso: {$existingEnrollment->progress}%\n";
    echo "Fecha de inscripción: {$existingEnrollment->enrolled_at}\n";
    exit(0);
}

// Intentar crear una nueva matrícula
try {
    echo "=== INTENTANDO CREAR NUEVA MATRÍCULA ===\n";
    
    // Usar una transacción para asegurar la integridad de los datos
    DB::beginTransaction();
    
    $now = now()->toDateTimeString();
    
    // Insertar la matrícula directamente con SQL
    $result = DB::insert(
        'INSERT INTO enrollments (user_id, course_id, status, progress, enrolled_at, created_at, updated_at) ' .
        'VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$studentId, $courseId, 'in_progress', 0, $now, $now, $now]
    );
    
    if (!$result) {
        throw new \Exception("No se pudo insertar la matrícula.");
    }
    
    $enrollmentId = DB::getPdo()->lastInsertId();
    
    // Confirmar la transacción
    DB::commit();
    
    echo "✓ ¡Matrícula creada exitosamente!\n";
    echo "ID de la matrícula: $enrollmentId\n";
    
} catch (\Exception $e) {
    // Revertir la transacción en caso de error
    DB::rollBack();
    
    echo "✗ Error al crear la matrícula: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error SQL
    $errorInfo = DB::connection()->getPdo()->errorInfo();
    if (!empty($errorInfo)) {
        echo "\nDetalles del error SQL:\n";
        echo "- Código: " . ($errorInfo[1] ?? 'N/A') . "\n";
        echo "- Mensaje: " . ($errorInfo[2] ?? 'N/A') . "\n";
    }
}
