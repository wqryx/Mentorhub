<?php

$host = '127.0.0.1';
$dbname = 'x';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "¡Conexión exitosa a la base de datos $dbname!\n\n";
    
    // Listar tablas
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "Tablas encontradas en $dbname:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    } else {
        echo "No se encontraron tablas en la base de datos $dbname.\n";
    }
    
} catch(PDOException $e) {
    if ($e->getCode() == 1049) {
        echo "La base de datos '$dbname' no existe.\n";
        echo "¿Deseas que intente crearla? (s/n): ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        if(trim($line) != 's'){
            exit("Saliendo...\n");
        }
        fclose($handle);
        
        try {
            $conn = new PDO("mysql:host=$host", $username, $password);
            $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname`;");
            echo "Base de datos '$dbname' creada exitosamente.\n";
        } catch(PDOException $e) {
            echo "Error al crear la base de datos: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Error de conexión: " . $e->getMessage() . "\n";
        echo "Detalles del error:\n";
        echo "- Código: " . $e->getCode() . "\n";
        echo "- Archivo: " . $e->getFile() . "\n";
        echo "- Línea: " . $e->getLine() . "\n";
    }
}
