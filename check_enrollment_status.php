<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar los valores permitidos para el campo status
try {
    echo "=== VALORES PERMITIDOS PARA EL CAMPO STATUS ===\n\n";
    
    // Obtener la definición de la columna status
    $statusColumn = DB::selectOne(
        "SHOW COLUMNS FROM enrollments WHERE Field = 'status'"
    );
    
    if (!$statusColumn) {
        die("No se pudo encontrar la columna 'status' en la tabla 'enrollments'\n");
    }
    
    echo "Tipo de columna: {$statusColumn->Type}\n";
    
    // Extraer los valores ENUM si es una columna ENUM
    if (preg_match("/^enum\((.*)\)$/", $statusColumn->Type, $matches)) {
        $enumValues = str_getcsv($matches[1], ",", "'");
        echo "Valores permitidos: " . implode(", ", $enumValues) . "\n";
    } else {
        echo "No es una columna ENUM. Tipo: {$statusColumn->Type}\n";
    }
    
    // Mostrar valores únicos existentes en la tabla
    $existingStatuses = DB::table('enrollments')
        ->select('status')
        ->distinct()
        ->pluck('status')
        ->toArray();
    
    echo "Valores existentes en la tabla: " . (empty($existingStatuses) ? 'Ninguno' : implode(", ", $existingStatuses)) . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
