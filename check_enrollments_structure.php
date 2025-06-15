<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Obtener la estructura de la tabla enrollments
    $structure = DB::select("SHOW COLUMNS FROM enrollments");
    
    echo "Estructura de la tabla 'enrollments':\n";
    echo str_pad("Campo", 20) . "| " . str_pad("Tipo", 30) . "| Nulo | Clave | Valor por defecto\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($structure as $column) {
        echo str_pad($column->Field, 20) . "| " . 
             str_pad($column->Type, 30) . "| " . 
             str_pad($column->Null, 4) . " | " .
             str_pad($column->Key, 5) . " | " .
             $column->Default . "\n";
    }
    
    // Verificar restricciones de clave foránea
    echo "\nRestricciones de clave foránea:\n";
    $constraints = DB::select("
        SELECT 
            TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, 
            REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE 
            TABLE_SCHEMA = DATABASE() AND 
            REFERENCED_TABLE_NAME IS NOT NULL AND
            TABLE_NAME = 'enrollments'
    ");
    
    if (empty($constraints)) {
        echo "No se encontraron restricciones de clave foránea.\n";
    } else {
        foreach ($constraints as $constraint) {
            echo "- {$constraint->COLUMN_NAME} referencia a {$constraint->REFERENCED_TABLE_NAME}({$constraint->REFERENCED_COLUMN_NAME})\n";
        }
    }
    
    // Mostrar índices
    echo "\nÍndices en la tabla 'enrollments':\n";
    $indexes = DB::select("SHOW INDEX FROM enrollments");
    
    if (empty($indexes)) {
        echo "No se encontraron índices.\n";
    } else {
        $currentIndex = '';
        foreach ($indexes as $index) {
            if ($currentIndex !== $index->Key_name) {
                $currentIndex = $index->Key_name;
                $unique = $index->Non_unique ? "No único" : "Único";
                echo "\n- $currentIndex ($unique): ";
            }
            echo $index->Column_name . ", ";
        }
        echo "\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
