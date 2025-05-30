<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

function info($message) {
    echo "\033[32m" . $message . "\033[0m\n";
}

function error($message) {
    echo "\033[31m" . $message . "\033[0m\n";
}

try {
    // 1. Verificar conexión a la base de datos
    info("Conectando a la base de datos mentohub...");
    $connection = DB::connection()->getPdo();
    info("✓ Conexión exitosa a la base de datos: " . DB::connection()->getDatabaseName());
    
    // 2. Mostrar la estructura actual de la tabla users
    $columns = Schema::getColumnListing('users');
    info("\nColumnas en la tabla users:");
    foreach ($columns as $column) {
        info("  - " . $column);
    }
    
    // 3. Crear usuarios de prueba
    $users = [
        [
            'name' => 'Administrador',
            'email' => 'admin@mentohub.com',
            'password' => Hash::make('password'),
            'role' => 'Admin'
        ],
        [
            'name' => 'Mentor Principal',
            'email' => 'mentor@mentohub.com',
            'password' => Hash::make('password'),
            'role' => 'Mentor'
        ],
        [
            'name' => 'Estudiante Ejemplo',
            'email' => 'estudiante@mentohub.com',
            'password' => Hash::make('password'),
            'role' => 'Estudiante'
        ],
        [
            'name' => 'Invitado',
            'email' => 'invitado@mentohub.com',
            'password' => Hash::make('password'),
            'role' => 'Guest'
        ]
    ];
    
    info("\nCreando usuarios...");
    
    foreach ($users as $userData) {
        $user = DB::table('users')->where('email', $userData['email'])->first();
        
        if (!$user) {
            // Crear el usuario adaptándonos a la estructura actual
            $userData_db = [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Si existe la columna email_verified_at, la añadimos
            if (in_array('email_verified_at', $columns)) {
                $userData_db['email_verified_at'] = now();
            }
            
            // Si existe la columna remember_token, la añadimos
            if (in_array('remember_token', $columns)) {
                $userData_db['remember_token'] = Str::random(10);
            }
            
            $userId = DB::table('users')->insertGetId($userData_db);
            
            info("✓ Usuario creado: " . $userData['email']);
            
            // Asignar rol al usuario
            $roleId = DB::table('roles')->where('name', $userData['role'])->value('id');
            
            if ($roleId) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId
                ]);
                
                info("  - Rol asignado: " . $userData['role']);
            }
        } else {
            info("✓ Usuario ya existe: " . $userData['email']);
            
            // Verificar si ya tiene el rol asignado
            $roleId = DB::table('roles')->where('name', $userData['role'])->value('id');
            
            if ($roleId) {
                $hasRole = DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->where('role_id', $roleId)
                    ->exists();
                    
                if (!$hasRole) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $roleId,
                        'model_type' => 'App\\Models\\User',
                        'model_id' => $user->id
                    ]);
                    
                    info("  - Rol asignado: " . $userData['role']);
                } else {
                    info("  - Ya tiene el rol: " . $userData['role']);
                }
            }
        }
    }
    
    info("\n¡Configuración completada con éxito!");
    info("Puedes iniciar sesión con cualquiera de estos usuarios usando la contraseña: password");
    info("  - Admin: admin@mentohub.com");
    info("  - Mentor: mentor@mentohub.com");
    info("  - Estudiante: estudiante@mentohub.com");
    info("  - Invitado: invitado@mentohub.com");
    
} catch (Exception $e) {
    error("Error: " . $e->getMessage());
    error("Archivo: " . $e->getFile() . " (Línea: " . $e->getLine() . ")");
}
