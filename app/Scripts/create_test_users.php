<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

function info($message) {
    echo "\033[32m" . $message . "\033[0m\n";
}

function error($message) {
    echo "\033[31m" . $message . "\033[0m\n";
}

try {
    // Crear roles si no existen
    $roles = ['Admin', 'Mentor', 'Estudiante', 'Guest'];
    foreach ($roles as $roleName) {
        Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    }
    
    // Crear usuarios de prueba
    $users = [
        [
            'name' => 'Administrador',
            'email' => 'admin@mentohub.com',
            'password' => bcrypt('password'),
            'roles' => ['Admin']
        ],
        [
            'name' => 'Mentor Principal',
            'email' => 'mentor@mentohub.com',
            'password' => bcrypt('password'),
            'roles' => ['Mentor']
        ],
        [
            'name' => 'Estudiante Ejemplo',
            'email' => 'estudiante@mentohub.com',
            'password' => bcrypt('password'),
            'roles' => ['Estudiante']
        ],
        [
            'name' => 'Invitado',
            'email' => 'invitado@mentohub.com',
            'password' => bcrypt('password'),
            'roles' => ['Guest']
        ]
    ];
    
    foreach ($users as $userData) {
        $user = User::where('email', $userData['email'])->first();
        
        if (!$user) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'email_verified_at' => now()
            ]);
            
            foreach ($userData['roles'] as $role) {
                $user->assignRole($role);
            }
            
            info("✓ Usuario creado: {$userData['email']} (Roles: " . implode(', ', $userData['roles']) . ")");
        } else {
            info("✓ Usuario ya existe: {$userData['email']}");
        }
    }
    
    info("\n¡Usuarios de prueba creados correctamente!");
    info("Puedes iniciar sesión con cualquiera de estos usuarios usando la contraseña: password");
    
} catch (Exception $e) {
    error("Error: " . $e->getMessage());
}
