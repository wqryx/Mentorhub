<?php

$host = '127.0.0.1';
$db   = 'x';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    echo "Conectado a la base de datos '$db'\n\n";
    
    // Verificar si la tabla cache ya existe
    $tableExists = $pdo->query("SHOW TABLES LIKE 'cache'")->rowCount() > 0;
    
    if (!$tableExists) {
        echo "Creando tabla 'cache'...\n";
        
        $sql = "CREATE TABLE `cache` (
            `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
            `expiration` int(11) NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $pdo->exec($sql);
        echo "✅ Tabla 'cache' creada exitosamente.\n";
    } else {
        echo "ℹ️ La tabla 'cache' ya existe.\n";
    }
    
    // Verificar si la tabla cache_locks existe
    $tableExists = $pdo->query("SHOW TABLES LIKE 'cache_locks'")->rowCount() > 0;
    
    if (!$tableExists) {
        echo "\nCreando tabla 'cache_locks'...\n";
        
        $sql = "CREATE TABLE `cache_locks` (
            `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `expiration` int(11) NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $pdo->exec($sql);
        echo "✅ Tabla 'cache_locks' creada exitosamente.\n";
    } else {
        echo "ℹ️ La tabla 'cache_locks' ya existe.\n";
    }
    
    echo "\n¡Tablas de caché creadas exitosamente!\n";
    echo "Ahora deberías poder iniciar sesión sin problemas.\n\n";
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
