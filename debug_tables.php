<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

function showTableStructure($tableName) {
    if (!Schema::hasTable($tableName)) {
        echo "La tabla '$tableName' no existe.\n";
        return;
    }
    
    echo "\nEstructura de la tabla '$tableName':\n";
    $columns = Schema::getColumnListing($tableName);
    print_r($columns);
    
    // Mostrar algunos datos de ejemplo
    try {
        $data = DB::table($tableName)->select($columns)->limit(2)->get();
        echo "\nDatos de ejemplo:\n";
        print_r($data->toArray());
    } catch (\Exception $e) {
        echo "Error al obtener datos: " . $e->getMessage() . "\n";
    }
    echo "\n" . str_repeat("=", 80) . "\n";
}

// Mostrar estructura de tablas relevantes
$tables = ['users', 'courses', 'enrollments', 'model_has_roles', 'roles'];

foreach ($tables as $table) {
    showTableStructure($table);
}

// Mostrar usuarios con rol de estudiante
try {
    echo "\nUsuarios con rol de estudiante:\n";
    $students = DB::table('users')
        ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'student')
        ->select('users.id', 'users.name', 'users.email')
        ->get();
    
    foreach ($students as $student) {
        echo "- ID: " . $student->id . ", Nombre: " . $student->name . ", Email: " . $student->email . "\n";
    }
} catch (\Exception $e) {
    echo "Error al obtener estudiantes: " . $e->getMessage() . "\n";
}
