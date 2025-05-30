<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Configuración de la base de datos
$dbConfig = [
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'x',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
];

// Inicializar Eloquent
$db = new DB;
$db->addConnection($dbConfig);
$db->setAsGlobal();
$db->bootEloquent();

try {
    // Verificar conexión
    DB::connection()->getPdo();
    echo "Conexión exitosa a la base de datos.\n";
    
    // Verificar si la tabla users tiene la columna 'role'
    if (!DB::schema()->hasColumn('users', 'role')) {
        echo "Agregando columna 'role' a la tabla 'users'...\n";
        
        // Agregar la columna 'role' si no existe
        DB::statement('ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT "student" AFTER email');
        echo "Columna 'role' agregada correctamente.\n";
    } else {
        echo "La columna 'role' ya existe en la tabla 'users'.\n";
    }
    
    // Actualizar roles basados en datos existentes (si es necesario)
    // Por ejemplo, si tienes una columna 'course' podrías usarla para identificar mentores/administradores
    // Esto es solo un ejemplo - ajústalo según tus necesidades
    
    // Contar usuarios
    $count = DB::table('users')->count();
    echo "\nTotal de usuarios en la base de datos: " . $count . "\n";
    
    // Mostrar los primeros 5 usuarios con sus roles
    echo "\nPrimeros 5 usuarios con sus roles:\n";
    $users = DB::table('users')->select('id', 'name', 'email', 'role')->take(5)->get();
    
    foreach ($users as $user) {
        echo "ID: " . $user->id . 
             " | Nombre: " . str_pad($user->name, 20) . 
             " | Email: " . str_pad($user->email, 30) . 
             " | Rol: " . ($user->role ?? 'No definido') . "\n";
    }
    
    echo "\nPara actualizar manualmente los roles, puedes usar las siguientes consultas SQL:\n";
    echo "-- Establecer un usuario como administrador\n";
    echo "UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';\n\n";
    
    echo "-- Establecer un usuario como mentor\n";
    echo "UPDATE users SET role = 'mentor' WHERE email = 'mentor@example.com';\n";
    
} catch (\Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
