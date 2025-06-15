<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function printHeader($title) {
    echo "\n=== " . strtoupper($title) . " ===\n\n";
}

try {
    // 1. Verificar si la tabla enrollments existe
    printHeader("VERIFICACIÓN DE TABLA ENROLLMENTS");
    
    $tableExists = Schema::hasTable('enrollments');
    echo "¿Existe la tabla 'enrollments'? " . ($tableExists ? 'Sí' : 'No') . "\n";
    
    if (!$tableExists) {
        die("La tabla 'enrollments' no existe en la base de datos.\n");
    }
    
    // 2. Mostrar la estructura de la tabla
    printHeader("ESTRUCTURA DE LA TABLA ENROLLMENTS");
    
    $columns = DB::select('SHOW COLUMNS FROM enrollments');
    
    echo str_pad("Campo", 30) . " | " . str_pad("Tipo", 30) . " | Nulo | Clave | Valor por defecto\n";
    echo str_repeat("-", 90) . "\n";
    
    foreach ($columns as $column) {
        echo str_pad($column->Field, 30) . " | " . 
             str_pad($column->Type, 30) . " | " . 
             str_pad($column->Null, 4) . " | " .
             str_pad($column->Key, 5) . " | " .
             ($column->Default ?? 'NULL') . "\n";
    }
    
    // 3. Contar registros
    printHeader("ESTADÍSTICAS DE ENROLLMENTS");
    
    $totalEnrollments = DB::table('enrollments')->count();
    echo "Total de matrículas: $totalEnrollments\n";
    
    // 4. Mostrar algunos registros de ejemplo (máximo 5)
    if ($totalEnrollments > 0) {
        $enrollments = DB::table('enrollments')
            ->select('*')
            ->limit(5)
            ->get();
            
        printHeader("EJEMPLOS DE MATRÍCULAS (máximo 5)");
        
        foreach ($enrollments as $enrollment) {
            echo "\nMatrícula ID: " . $enrollment->id . "\n";
            echo "- User ID: " . ($enrollment->user_id ?? 'NULL') . "\n";
            echo "- Course ID: " . ($enrollment->course_id ?? 'NULL') . "\n";
            echo "- Status: " . ($enrollment->status ?? 'NULL') . "\n";
            echo "- Enrollment Date: " . ($enrollment->enrollment_date ?? 'NULL') . "\n";
            echo "- Created At: " . ($enrollment->created_at ?? 'NULL') . "\n";
            echo "- Updated At: " . ($enrollment->updated_at ?? 'NULL') . "\n";
        }
    }
    
    // 5. Verificar si hay cursos activos
    printHeader("CURSOS ACTIVOS");
    
    $activeCourses = DB::table('courses')
        ->where('is_active', 1)
        ->count();
        
    echo "Total de cursos activos: $activeCourses\n";
    
    if ($activeCourses > 0) {
        $courses = DB::table('courses')
            ->where('is_active', 1)
            ->select('id', 'name', 'code')
            ->limit(5)
            ->get();
            
        echo "\nAlgunos cursos activos:\n";
        foreach ($courses as $course) {
            echo "- ID: {$course->id}, Código: {$course->code}, Nombre: {$course->name}\n";
        }
    }
    
} catch (\Exception $e) {
    printHeader("ERROR");
    echo "✗ Error: " . $e->getMessage() . "\n";
    
    // Mostrar información detallada del error
    if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
        echo "Error detallado: " . $e->getPrevious()->getMessage() . "\n";
    }
}
