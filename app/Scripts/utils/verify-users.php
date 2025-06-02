<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFICACIÓN DE USUARIOS PRINCIPALES DE MENTORHUB ===\n\n";

// Obtener los 3 usuarios principales
$users = DB::table('users')
    ->leftJoin('model_has_roles', function($join) {
        $join->on('users.id', '=', 'model_has_roles.model_id')
             ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
    })
    ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->select('users.id', 'users.name', 'users.email', 'roles.name as role')
    ->whereIn('users.id', [1, 2, 3])
    ->orderBy('users.id')
    ->get();

if (count($users) === 0) {
    echo "No se encontraron usuarios con IDs 1, 2 o 3.\n";
} else {
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Nombre: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Rol: {$user->role}\n";
        
        // Mostrar contraseña basada en el rol
        echo "Contraseña: ";
        if ($user->role === 'admin') {
            echo "Admin123!";
        } elseif ($user->role === 'mentor') {
            echo "Mentor123!";
        } elseif ($user->role === 'student') {
            echo "Estudiante123!";
        } else {
            echo "Desconocida";
        }
        
        echo "\n\n";
    }
}

echo "=== FIN DE LA VERIFICACIÓN ===\n";
