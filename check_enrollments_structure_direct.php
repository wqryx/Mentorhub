<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== ESTRUCTURA DE LA TABLA ENROLLMENTS ===\n\n";
    
    // Obtener la estructura de la tabla
    $structure = DB::select("SHOW COLUMNS FROM enrollments");
    
    // Mostrar información de columnas
    echo "Columnas de la tabla 'enrollments':\n";
    echo str_pad("Campo", 20) . " | " . str_pad("Tipo", 30) . " | Nulo | Clave | Valor por defecto\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($structure as $column) {
        echo str_pad($column->Field, 20) . " | " . 
             str_pad($column->Type, 30) . " | " . 
             str_pad($column->Null, 4) . " | " .
             str_pad($column->Key, 5) . " | " .
             ($column->Default ?? 'NULL') . "\n";
    }
    
    // Mostrar restricciones
    echo "\n=== RESTRICCIONES DE LA TABLA ===\n";
    $createTable = DB::select("SHOW CREATE TABLE enrollments");
    echo $createTable[0]->{'Create Table'} . "\n";
    
    // Mostrar datos de ejemplo
    echo "\n=== DATOS DE EJEMPLO (máximo 5 registros) ===\n";
    $enrollments = DB::select("SELECT * FROM enrollments LIMIT 5");
    
    if (empty($enrollments)) {
        echo "No hay registros en la tabla enrollments.\n";
    } else {
        foreach ($enrollments as $enrollment) {
            echo "\nMatrícula ID: " . $enrollment->id . "\n";
            echo "- User ID: " . $enrollment->user_id . "\n";
            echo "- Course ID: " . $enrollment->course_id . "\n";
            echo "- Status: " . ($enrollment->status ?? 'No definido') . "\n";
            echo "- Progress: " . ($enrollment->progress ?? '0') . "%\n";
            echo "- Enrolled At: " . ($enrollment->enrolled_at ?? 'No definido') . "\n";
            echo "- Created At: " . ($enrollment->created_at ?? 'No definido') . "\n";
            echo "- Updated At: " . ($enrollment->updated_at ?? 'No definido') . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error SQL
    $errorInfo = DB::connection()->getPdo()->errorInfo();
    if (!empty($errorInfo)) {
        echo "\nDetalles del error SQL:\n";
        echo "- Código: " . ($errorInfo[1] ?? 'N/A') . "\n";
        echo "- Mensaje: " . ($errorInfo[2] ?? 'N/A') . "\n";
    }
}
