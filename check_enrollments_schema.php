<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Obtener la estructura de la tabla enrollments
    $columns = DB::select("SHOW COLUMNS FROM enrollments");
    
    echo "=== ESTRUCTURA DE LA TABLA ENROLLMENTS ===\n\n";
    echo str_pad("Campo", 25) . " | " . str_pad("Tipo", 25) . " | Nulo | Clave | Valor por defecto\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($columns as $column) {
        echo str_pad($column->Field, 25) . " | " . 
             str_pad($column->Type, 25) . " | " . 
             str_pad($column->Null, 4) . " | " .
             str_pad($column->Key, 5) . " | " .
             ($column->Default ?? 'NULL') . "\n";
    }
    
    // Mostrar restricciones
    echo "\n=== RESTRICCIONES DE LA TABLA ===\n";
    $createTable = DB::select("SHOW CREATE TABLE enrollments");
    echo $createTable[0]->{'Create Table'} . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
