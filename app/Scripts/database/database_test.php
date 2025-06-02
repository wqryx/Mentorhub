<?php

$host = '127.0.0.1';
$dbname = 'mentorhub';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "¡Conexión exitosa a la base de datos $dbname!\n\n";
    
    // Listar tablas
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "Tablas encontradas:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    } else {
        echo "No se encontraron tablas en la base de datos.\n";
    }
    
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
    echo "Detalles del error:\n";
    echo "- Código: " . $e->getCode() . "\n";
    echo "- Archivo: " . $e->getFile() . "\n";
    echo "- Línea: " . $e->getLine() . "\n";
}
