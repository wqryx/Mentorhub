<?php

$host = '127.0.0.1';
$dbname = 'mentorhub';
$username = 'root';
$password = '';

try {
    // Conectar al servidor MySQL sin seleccionar una base de datos
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado al servidor MySQL.\n";
    
    // Eliminar la base de datos si existe
    echo "Eliminando base de datos '$dbname' si existe...\n";
    $conn->exec("DROP DATABASE IF EXISTS `$dbname`");
    
    // Crear la base de datos
    echo "Creando base de datos '$dbname'...\n";
    $conn->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "Base de datos '$dbname' creada exitosamente.\n\n";
    
    // Crear la tabla de migraciones
    echo "Creando tabla de migraciones...\n";
    $conn->exec("USE `$dbname`");
    $conn->exec("CREATE TABLE `migrations` (
        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `batch` int(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "Tabla de migraciones creada.\n\n";
    
    echo "Ahora puedes ejecutar las migraciones con: php artisan migrate\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Código de error: " . $e->getCode() . "\n";
}
