<?php

// Configuración de la base de datos
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'mentorhub';

try {
    // 1. Conectar al servidor MySQL sin seleccionar una base de datos
    $conn = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado al servidor MySQL.\n";
    
    // 2. Verificar si la base de datos existe
    $result = $conn->query("SHOW DATABASES LIKE '$dbName'");
    
    if ($result->rowCount() > 0) {
        echo "La base de datos '$dbName' ya existe.\n";
    } else {
        // 3. Crear la base de datos si no existe
        $conn->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Base de datos '$dbName' creada exitosamente.\n";
        
        // 4. Seleccionar la base de datos
        $conn->exec("USE `$dbName`");
        
        // Aquí podríamos agregar la creación de tablas si es necesario
    }
    
    // 5. Verificar la conexión a la base de datos específica
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos '$dbName'.\n";
    
    // 6. Verificar tablas importantes
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "\n¡Atención! La base de datos está vacía. Necesitas ejecutar las migraciones.\n";
        echo "Ejecuta: php artisan migrate\n";
    } else {
        echo "\nTablas existentes en la base de datos:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    }
    
    // 7. Verificar configuración de caché
    echo "\nConfiguración de caché actual:\n";
    $cacheConfig = [
        'CACHE_DRIVER' => 'file',
        'CACHE_PREFIX' => 'mentorhub_cache',
        'CACHE_DEFAULT' => 'file',
    ];
    
    foreach ($cacheConfig as $key => $value) {
        echo "$key: $value\n";
    }
    
    echo "\nSolución de problemas completada. Si necesitas ayuda adicional, verifica lo siguiente:\n";
    echo "1. Que el usuario '$dbUser' tenga permisos sobre la base de datos '$dbName'\n";
    echo "2. Que el archivo .env exista y tenga la configuración correcta\n";
    echo "3. Que las migraciones se hayan ejecutado correctamente (php artisan migrate)\n";
    
} catch(PDOException $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    echo "Código de error: " . $e->getCode() . "\n";
}
