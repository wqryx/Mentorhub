<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar si el usuario con ID 3 existe
$user = DB::table('users')->where('id', 3)->first();

if (!$user) {
    echo "El usuario con ID 3 no existe en la base de datos.\n";
    exit;
}

echo "Información del usuario con ID 3:\n";
echo "--------------------------------\n";
echo "Nombre: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";

// Verificar roles del usuario
$roles = DB::table('model_has_roles')
    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->where('model_has_roles.model_id', 3)
    ->pluck('roles.name')
    ->toArray();

echo "Roles: " . (count($roles) > 0 ? implode(', ', $roles) : 'Sin roles asignados') . "\n";

// Verificar si el usuario tiene el rol de estudiante
$isStudent = in_array('student', $roles);

echo "¿Es estudiante? " . ($isStudent ? 'Sí' : 'No') . "\n";

if (!$isStudent) {
    echo "\nAsignando rol de estudiante al usuario...\n";
    
    try {
        // Asignar el rol de estudiante (ID 3 según la migración)
        DB::table('model_has_roles')->insert([
            'role_id' => 3, // ID del rol 'student'
            'model_type' => 'App\\Models\\User',
            'model_id' => 3
        ]);
        
        echo "Rol de estudiante asignado correctamente.\n";
    } catch (\Exception $e) {
        echo "Error al asignar el rol de estudiante: " . $e->getMessage() . "\n";
    }
}
