<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Verificar si la tabla de enrollments existe
if (!Schema::hasTable('enrollments')) {
    echo "La tabla 'enrollments' no existe en la base de datos.\n";
    exit(1);
}

// Obtener la estructura de la tabla
$columns = Schema::getColumnListing('enrollments');
echo "Columnas en la tabla 'enrollments':\n";
print_r($columns);

// Verificar si hay cursos y usuarios
$courseCount = DB::table('courses')->count();
$userCount = DB::table('users')->count();

echo "\nTotal de cursos en la base de datos: " . $courseCount . "\n";
echo "Total de usuarios en la base de datos: " . $userCount . "\n";

// Mostrar los primeros 5 cursos
$courses = DB::table('courses')->select('id', 'title', 'status')->take(5)->get();
echo "\nPrimeros 5 cursos:\n";
foreach ($courses as $course) {
    echo "- ID: " . $course->id . ", Título: " . $course->title . ", Estado: " . $course->status . "\n";
}

// Mostrar los primeros 5 usuarios con rol de estudiante
$students = DB::table('users')
    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->where('roles.name', 'student')
    ->select('users.id', 'users.name', 'users.email')
    ->take(5)
    ->get();

echo "\nPrimeros 5 estudiantes:\n";
foreach ($students as $student) {
    echo "- ID: " . $student->id . ", Nombre: " . $student->name . ", Email: " . $student->email . "\n";
}
