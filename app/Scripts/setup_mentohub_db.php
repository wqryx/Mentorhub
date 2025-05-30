<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
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
    
    // 2. Verificar y crear la tabla de roles si no existe
    if (!Schema::hasTable('roles')) {
        info("Creando tabla de roles...");
        Schema::create('roles', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->timestamps();
        });
        info("✓ Tabla de roles creada correctamente");
    } else {
        info("✓ Tabla de roles ya existe");
    }
    
    // 3. Verificar y crear la tabla de model_has_roles si no existe
    if (!Schema::hasTable('model_has_roles')) {
        info("Creando tabla de model_has_roles...");
        Schema::create('model_has_roles', function ($table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_primary');
        });
        info("✓ Tabla de model_has_roles creada correctamente");
    } else {
        info("✓ Tabla de model_has_roles ya existe");
    }
    
    // 4. Crear roles si no existen
    $roles = ['Admin', 'Mentor', 'Estudiante', 'Guest'];
    
    foreach ($roles as $roleName) {
        $exists = DB::table('roles')->where('name', $roleName)->exists();
        if (!$exists) {
            DB::table('roles')->insert([
                'name' => $roleName,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            info("✓ Rol creado: " . $roleName);
        } else {
            info("✓ Rol ya existe: " . $roleName);
        }
    }
    
    // 5. Crear usuarios de prueba
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
    
    foreach ($users as $userData) {
        $user = DB::table('users')->where('email', $userData['email'])->first();
        
        if (!$user) {
            // Verificar que la tabla users existe y tiene las columnas necesarias
            if (!Schema::hasTable('users')) {
                info("Creando tabla de usuarios...");
                Schema::create('users', function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                });
                info("✓ Tabla de usuarios creada correctamente");
            }
            
            // Insertar el usuario
            $userId = DB::table('users')->insertGetId([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
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
            $hasRole = DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('role_id', $roleId)
                ->exists();
                
            if (!$hasRole && $roleId) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $user->id
                ]);
                
                info("  - Rol asignado: " . $userData['role']);
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
