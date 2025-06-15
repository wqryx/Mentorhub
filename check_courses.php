<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar si la tabla courses existe
if (!Schema::hasTable('courses')) {
    echo "La tabla courses no existe.\n";
    exit;
}

// Obtener todos los cursos
echo "Verificando cursos en la base de datos...\n";

$courses = DB::table('courses')->get();

echo "Total de cursos encontrados: " . $courses->count() . "\n\n";

if ($courses->isNotEmpty()) {
    echo "Lista de cursos:\n";
    echo "----------------\n";
    
    foreach ($courses as $course) {
        echo "ID: " . $course->id . "\n";
        echo "Nombre: " . ($course->name ?? 'N/A') . "\n";
        echo "Código: " . ($course->code ?? 'N/A') . "\n";
        echo "Creador ID: " . ($course->creator_id ?? 'N/A') . "\n";
        echo "Activo: " . ($course->is_active ? 'Sí' : 'No') . "\n";
        echo "----------------\n";
    }
} else {
    echo "No hay cursos en la base de datos.\n";
}
