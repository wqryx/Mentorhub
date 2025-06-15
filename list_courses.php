<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;

// Mostrar todos los cursos con sus columnas
try {
    // Obtener la primera fila para ver las columnas
    $firstCourse = Course::first();
    
    if (!$firstCourse) {
        echo "No hay cursos en la base de datos.\n";
        exit(0);
    }
    
    echo "Columnas disponibles en la tabla courses:\n";
    print_r($firstCourse->getAttributes());
    
    // Mostrar todos los cursos
    echo "\nCursos disponibles:\n";
    $courses = Course::all();
    
    foreach ($courses as $course) {
        echo "- ID: " . $course->id;
        if (isset($course->title)) echo ", Título: " . $course->title;
        if (isset($course->name)) echo ", Nombre: " . $course->name;
        if (isset($course->status)) echo ", Estado: " . $course->status;
        echo "\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if ($e->getPrevious()) {
        echo "Error detallado: " . $e->getPrevious()->getMessage() . "\n";
    }
}
