<?php

// Cargar el autoload de Laravel para acceder a las funciones de configuración
require __DIR__.'/vendor/autoload.php';

// Inicializar la aplicación Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Obtener la configuración de la base de datos
$dbConfig = [
    'DB_CONNECTION' => env('DB_CONNECTION', 'mysql'),
    'DB_HOST' => env('DB_HOST', '127.0.0.1'),
    'DB_PORT' => env('DB_PORT', '3306'),
    'DB_DATABASE' => env('DB_DATABASE', 'laravel'),
    'DB_USERNAME' => env('DB_USERNAME', 'root'),
    'DB_PASSWORD' => env('DB_PASSWORD', ''),
];

// Mostrar la configuración actual
echo "Configuración actual de la base de datos:\n";
echo "====================================\n";
foreach ($dbConfig as $key => $value) {
    echo "$key: $value\n";
}

// Verificar si el archivo .env existe
$envFile = __DIR__.'/.env';
if (file_exists($envFile)) {
    echo "\nEl archivo .env existe.\n";
    
    // Leer el contenido del archivo .env
    $envContent = file_get_contents($envFile);
    
    // Buscar la configuración de la base de datos
    if (preg_match('/DB_DATABASE=(.*)/', $envContent, $matches)) {
        echo "\nBase de datos configurada en .env: " . trim($matches[1], "'\" \t\n\r\0\x0B") . "\n";
    } else {
        echo "\nNo se encontró la configuración de DB_DATABASE en .env\n";
    }
    
    // Buscar errores de escritura
    if (stripos($envContent, 'mentohub') !== false) {
        echo "\n¡ADVERTENCIA! Se encontró 'mentohub' en el archivo .env. Debería ser 'mentorhub'.\n";
    }
} else {
    echo "\nEl archivo .env no existe. Debes crearlo a partir de .env.example\n";
}

// Verificar la conexión a la base de datos
try {
    $dsn = "mysql:host={$dbConfig['DB_HOST']};port={$dbConfig['DB_PORT']};dbname={$dbConfig['DB_DATABASE']}";
    $pdo = new PDO($dsn, $dbConfig['DB_USERNAME'], $dbConfig['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "\n¡Conexión exitosa a la base de datos '{$dbConfig['DB_DATABASE']}'!\n";
    
    // Verificar tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "\nTablas en la base de datos:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
} catch (PDOException $e) {
    echo "\nError al conectar a la base de datos: " . $e->getMessage() . "\n";
    
    // Sugerir soluciones basadas en el error
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        echo "\nPosible solución: La base de datos '{$dbConfig['DB_DATABASE']}' no existe.\n";
        echo "Puedes crearla con: CREATE DATABASE `{$dbConfig['DB_DATABASE']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
    } elseif (strpos($e->getMessage(), "Access denied") !== false) {
        echo "\nPosible solución: Credenciales incorrectas o el usuario no tiene permisos.\n";
    }
}
