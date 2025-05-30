<?php

// Este script elimina usuarios existentes si es posible y crea 4 nuevos usuarios con diferentes roles

// Cargamos la aplicación de Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;

// Función para mostrar mensajes en la consola
function info($message) {
    echo "\n\033[32m" . $message . "\033[0m\n";
}

function error($message) {
    echo "\n\033[31m" . $message . "\033[0m\n";
}

info('Iniciando script de gestión de usuarios...');

try {
    // Verificar la conexión a la base de datos
    info('Verificando la conexión a la base de datos...');
    DB::connection()->getPdo();
    info('✓ Conexión a la base de datos establecida correctamente: ' . DB::connection()->getDatabaseName());

    // Comprobar si existe la tabla users
    if (!Schema::hasTable('users')) {
        error('La tabla "users" no existe. Asegúrate de que las migraciones se han ejecutado.');
        exit(1);
    }
    
    // Comprobar si existe la tabla roles
    if (!Schema::hasTable('roles')) {
        error('La tabla "roles" no existe. Asegúrate de que las migraciones de spatie/laravel-permission se han ejecutado.');
        exit(1);
    }

    // En lugar de eliminar todos los usuarios, vamos a eliminar solo los usuarios con los emails que vamos a crear
    info('Eliminando usuarios con los emails que vamos a usar (si existen)...');
    $emails = ['admin@example.com', 'mentor@example.com', 'estudiante@example.com', 'invitado@example.com'];
    $count = User::whereIn('email', $emails)->delete();
    info("✓ {$count} usuarios eliminados correctamente.");

    // Crear los roles si no existen
    info('Verificando roles...');
    $roles = ['Admin', 'Mentor', 'Estudiante', 'Guest'];
    foreach ($roles as $roleName) {
        Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    }
    info('✓ Roles verificados correctamente.');

    // Crear 4 nuevos usuarios
    info('Creando nuevos usuarios...');

    // 1. Admin
    $admin = User::create([
        'name' => 'Administrador',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('Admin');
    info('✓ Usuario Administrador creado - Email: admin@example.com, Contraseña: password');

    // 2. Mentor
    $mentor = User::create([
        'name' => 'Mentor',
        'email' => 'mentor@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $mentor->assignRole('Mentor');
    info('✓ Usuario Mentor creado - Email: mentor@example.com, Contraseña: password');

    // 3. Estudiante
    $estudiante = User::create([
        'name' => 'Estudiante',
        'email' => 'estudiante@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $estudiante->assignRole('Estudiante');
    info('✓ Usuario Estudiante creado - Email: estudiante@example.com, Contraseña: password');

    // 4. Invitado
    $invitado = User::create([
        'name' => 'Invitado',
        'email' => 'invitado@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $invitado->assignRole('Guest');
    info('✓ Usuario Invitado creado - Email: invitado@example.com, Contraseña: password');

    info('¡Todos los usuarios han sido creados correctamente!');
    
    // Resumen
    info("\nRESUMEN DE USUARIOS CREADOS:");
    info("----------------------------");
    info("ADMINISTRADOR:");
    info("Email: admin@example.com");
    info("Contraseña: password");
    info("");
    info("MENTOR:");
    info("Email: mentor@example.com");
    info("Contraseña: password");
    info("");
    info("ESTUDIANTE:");
    info("Email: estudiante@example.com");
    info("Contraseña: password");
    info("");
    info("INVITADO:");
    info("Email: invitado@example.com");
    info("Contraseña: password");

} catch (\Exception $e) {
    error('Error: ' . $e->getMessage());
    exit(1);
}
