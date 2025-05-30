<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Mostrar la configuración de la base de datos
echo "=== Configuración de conexión del backend (Laravel) ===\n";
echo "DB_HOST: " . config('database.connections.mysql.host') . "\n";
echo "DB_PORT: " . config('database.connections.mysql.port') . "\n";
echo "DB_DATABASE: " . config('database.connections.mysql.database') . "\n";
echo "DB_USERNAME: " . config('database.connections.mysql.username') . "\n";

// Verificar la conexión
try {
    DB::connection()->getPdo();
    echo "✓ Conexión exitosa a la base de datos: " . DB::connection()->getDatabaseName() . "\n";
    
    // Verificar que podemos obtener datos
    $users = DB::table('users')->count();
    echo "✓ Número de usuarios en la base de datos: " . $users . "\n";
    
    $roles = DB::table('roles')->count();
    echo "✓ Número de roles en la base de datos: " . $roles . "\n";
    
} catch (Exception $e) {
    echo "✗ Error de conexión: " . $e->getMessage() . "\n";
}
