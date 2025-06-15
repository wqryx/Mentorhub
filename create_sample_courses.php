<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Verificar si ya hay cursos en la base de datos
$existingCourses = DB::table('courses')->count();

if ($existingCourses > 0) {
    echo "Ya existen $existingCourses cursos en la base de datos. No se crearán cursos de ejemplo.\n";
    exit;
}

// Crear algunos cursos de ejemplo
$courses = [
    [
        'name' => 'Introducción a Laravel',
        'code' => 'LAR-101',
        'description' => 'Aprende los conceptos básicos de Laravel, el framework de PHP más popular.',
        'level' => 'Principiante',
        'credits' => 3,
        'hours_per_week' => 4,
        'is_active' => true,
        'start_date' => Carbon::now()->addWeek()->format('Y-m-d'),
        'end_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
        'classroom' => 'Aula Virtual',
        'schedule' => 'Lunes y Miércoles 18:00-20:00',
        'creator_id' => 1, // Asumiendo que el usuario con ID 1 es un administrador
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Desarrollo Frontend con Vue.js',
        'code' => 'VUE-201',
        'description' => 'Domina Vue.js para crear aplicaciones web interactivas y reactivas.',
        'level' => 'Intermedio',
        'credits' => 4,
        'hours_per_week' => 5,
        'is_active' => true,
        'start_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
        'end_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
        'classroom' => 'Aula Virtual',
        'schedule' => 'Martes y Jueves 16:00-18:30',
        'creator_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'Bases de Datos con MySQL',
        'code' => 'SQL-101',
        'description' => 'Aprende a diseñar y consultar bases de datos relacionales con MySQL.',
        'level' => 'Principiante',
        'credits' => 3,
        'hours_per_week' => 4,
        'is_active' => true,
        'start_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        'end_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
        'classroom' => 'Aula Virtual',
        'schedule' => 'Viernes 15:00-19:00',
        'creator_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

// Insertar los cursos en la base de datos
try {
    DB::beginTransaction();
    
    foreach ($courses as $course) {
        $courseId = DB::table('courses')->insertGetId($course);
        echo "Curso creado: {$course['name']} (ID: $courseId)\n";
    }
    
    DB::commit();
    echo "\n¡Cursos creados exitosamente!\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error al crear los cursos: " . $e->getMessage() . "\n";
}
