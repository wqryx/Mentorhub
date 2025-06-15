<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Verificar si la tabla de enrollments existe
if (!Schema::hasTable('enrollments')) {
    echo "La tabla 'enrollments' no existe.\n";
    exit(1);
}

// Obtener la estructura de la tabla
echo "Estructura de la tabla 'enrollments':\n";
$columns = Schema::getColumnListing('enrollments');
print_r($columns);

// Mostrar las restricciones de la tabla
try {
    echo "\nRestricciones de la tabla 'enrollments':\n";
    $constraints = DB::select("SHOW CREATE TABLE enrollments");
    echo $constraints[0]->{'Create Table'} . "\n";
} catch (\Exception $e) {
    echo "No se pudieron obtener las restricciones: " . $e->getMessage() . "\n";
}

// Mostrar algunas matrículas existentes
try {
    echo "\nMatrículas existentes (máximo 5):\n";
    $enrollments = DB::table('enrollments')->limit(5)->get();
    print_r($enrollments);
} catch (\Exception $e) {
    echo "No se pudieron obtener las matrículas: " . $e->getMessage() . "\n";
}
