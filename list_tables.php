<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Obtener todas las tablas en la base de datos
    $tables = DB::select('SHOW TABLES');
    
    // El nombre de la columna varía según la configuración de la base de datos
    $dbName = DB::connection()->getDatabaseName();
    $tablesColumnName = 'Tables_in_' . str_replace('-', '_', $dbName);
    
    echo "=== TABLAS EN LA BASE DE DATOS ===\n\n";
    
    foreach ($tables as $table) {
        $tableName = $table->{$tablesColumnName};
        echo "- $tableName\n";
        
        // Mostrar columnas de la tabla
        $columns = DB::select("SHOW COLUMNS FROM `$tableName`");
        echo "  Columnas: ";
        $columnNames = array_map(function($col) { return $col->Field; }, $columns);
        echo implode(', ', $columnNames) . "\n\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
