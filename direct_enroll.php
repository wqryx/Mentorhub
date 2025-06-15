<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Mostrar la estructura de la tabla enrollments
try {
    echo "=== ESTRUCTURA DE LA TABLA ENROLLMENTS ===\n";
    $columns = DB::select("SHOW COLUMNS FROM enrollments");
    
    echo "Columnas y sus propiedades:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field}: Tipo={$column->Type}, Nulo={$column->Null}, Clave={$column->Key}, Valor por defecto={$column->Default}\n";
    }
    
    // Mostrar restricciones
    echo "\nRestricciones de la tabla:\n";
    $createTable = DB::select("SHOW CREATE TABLE enrollments");
    echo $createTable[0]->{'Create Table'} . "\n";
    
    // Mostrar datos de ejemplo
    echo "\nDatos de ejemplo (máximo 5 registros):\n";
    $enrollments = DB::select("SELECT * FROM enrollments LIMIT 5");
    print_r($enrollments);
    
} catch (\Exception $e) {
    echo "Error al obtener la estructura de la tabla: " . $e->getMessage() . "\n";
}

// Intentar insertar una matrícula manualmente
try {
    echo "\n=== INTENTANDO INSERTAR UNA NUEVA MATRÍCULA ===\n";
    
    // Verificar si ya existe una matrícula para este estudiante y curso
    $studentId = 3; // ID del estudiante
    $courseId = 4;  // ID del curso
    
    $exists = DB::select("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?", [$studentId, $courseId]);
    
    if (!empty($exists)) {
        echo "El estudiante ya está inscrito en este curso. ID de matrícula: " . $exists[0]->id . "\n";
    } else {
        // Intentar insertar la matrícula
        $result = DB::insert("INSERT INTO enrollments (user_id, course_id, status, progress, enrolled_at, created_at, updated_at) VALUES (?, ?, 'in_progress', 0, NOW(), NOW(), NOW())", 
            [$studentId, $courseId]);
            
        if ($result) {
            $enrollmentId = DB::getPdo()->lastInsertId();
            echo "¡Matrícula creada exitosamente! ID: $enrollmentId\n";
        } else {
            echo "No se pudo crear la matrícula.\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error al intentar insertar la matrícula: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error
    if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
        echo "\nDetalles del error SQL:\n";
        echo "- Código de error: " . $e->getCode() . "\n";
        
        // Intentar obtener más información del error
        $errorInfo = DB::connection()->getPdo()->errorInfo();
        if (!empty($errorInfo)) {
            echo "- Error SQL: " . ($errorInfo[2] ?? 'No disponible') . "\n";
            echo "- Código SQL: " . ($errorInfo[1] ?? 'No disponible') . "\n";
            echo "- Estado SQL: " . ($errorInfo[0] ?? 'No disponible') . "\n";
        }
    }
}
