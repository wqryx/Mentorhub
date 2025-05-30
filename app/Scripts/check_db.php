<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Configuración de la base de datos
$dbConfig = [
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'x', // Asumiendo que la base de datos se llama 'x' según lo mencionado
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
    
    // Verificar si la tabla users existe
    if (DB::schema()->hasTable('users')) {
        echo "La tabla 'users' existe.\n";
        
        // Obtener la estructura de la tabla
        $columns = DB::select('DESCRIBE users');
        echo "\nEstructura de la tabla 'users':\n";
        echo str_pad('Campo', 20) . str_pad('Tipo', 20) . "Nulo\n";
        echo str_repeat('-', 50) . "\n";
        
        foreach ($columns as $column) {
            echo str_pad($column->Field, 20) . 
                 str_pad($column->Type, 20) . 
                 $column->Null . "\n";
        }
        
        // Contar usuarios
        $count = DB::table('users')->count();
        echo "\nTotal de usuarios en la base de datos: " . $count . "\n";
        
        // Mostrar los primeros 5 usuarios
        if ($count > 0) {
            echo "\nPrimeros 5 usuarios:\n";
            $users = DB::table('users')->select('id', 'name', 'email', 'role')->take(5)->get();
            
            foreach ($users as $user) {
                echo "ID: " . $user->id . 
                     " | Nombre: " . str_pad($user->name, 20) . 
                     " | Email: " . str_pad($user->email, 30) . 
                     " | Rol: " . ($user->role ?? 'No definido') . "\n";
            }
        }
    } else {
        echo "La tabla 'users' no existe en la base de datos.\n";
    }
    
} catch (\Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage() . "\n");
}
