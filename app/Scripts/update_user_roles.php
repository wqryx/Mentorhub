<?php

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Database configuration
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

// Initialize Eloquent
$db = new DB;
$db->addConnection($dbConfig);
$db->setAsGlobal();
$db->bootEloquent();

try {
    // Check connection
    DB::connection()->getPdo();
    echo "Successfully connected to the database.\n";
    
    // Add role column if it doesn't exist
    if (!DB::schema()->hasColumn('users', 'role')) {
        echo "Adding 'role' column to users table...\n";
        DB::statement('ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT "student" AFTER email');
        echo "Column 'role' added successfully.\n";
    }
    
    // Count users
    $count = DB::table('users')->count();
    echo "\nTotal users in the database: " . $count . "\n";
    
    // Show first 5 users with their current roles
    $users = DB::table('users')->select('id', 'name', 'email', 'role')->take(5)->get();
    
    echo "\nFirst 5 users with their current roles:\n";
    echo str_repeat('-', 80) . "\n";
    echo str_pad('ID', 5) . str_pad('Name', 30) . str_pad('Email', 35) . "Role\n";
    echo str_repeat('-', 80) . "\n";
    
    foreach ($users as $user) {
        echo str_pad($user->id, 5) . 
             str_pad(substr($user->name, 0, 28), 30) . 
             str_pad(substr($user->email, 0, 33), 35) . 
             ($user->role ?? 'Not set') . "\n";
    }
    
    // Actualizar roles de los usuarios automáticamente
    $usersToUpdate = [
        'admin@mentorhub.com' => 'admin',
        'mentor1@mentorhub.com' => 'mentor',
        'mentor2@mentorhub.com' => 'mentor',
        'student1@mentorhub.com' => 'student',
        'student2@mentorhub.com' => 'student'
    ];
    
    echo "\nActualizando roles de usuarios...\n";
    
    foreach ($usersToUpdate as $email => $role) {
        $user = DB::table('users')->where('email', $email)->first();
        
        if ($user) {
            DB::table('users')
                ->where('email', $email)
                ->update(['role' => $role]);
                
            echo "Actualizado: " . str_pad($user->name, 20) . " -> " . $role . "\n";
        } else {
            echo "Advertencia: Usuario con email {$email} no encontrado.\n";
        }
    }
    
    // Mostrar los usuarios actualizados
    echo "\nUsuarios actualizados con sus nuevos roles:\n";
    echo str_repeat('-', 80) . "\n";
    echo str_pad('ID', 5) . str_pad('Nombre', 25) . str_pad('Email', 35) . "Rol\n";
    echo str_repeat('-', 80) . "\n";
    
    $users = DB::table('users')->select('id', 'name', 'email', 'role')->orderBy('role')->get();
    
    foreach ($users as $user) {
        echo str_pad($user->id, 5) . 
             str_pad(substr($user->name, 0, 23), 25) . 
             str_pad(substr($user->email, 0, 33), 35) . 
             $user->role . "\n";
    }
    
} catch (\Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
