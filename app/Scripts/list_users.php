<?php

$host = '127.0.0.1';
$dbname = 'mentorhub';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener estructura de la tabla users
    $stmt = $conn->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Campos en la tabla users:\n";
    echo implode(", ", $columns) . "\n\n";
    
    // Obtener usuarios (solo mostrando los campos relevantes para seguridad)
    $stmt = $conn->query("SELECT id, name, email, created_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "Usuarios en el sistema:\n";
        echo str_pad("ID", 5) . " | " . str_pad("Nombre", 20) . " | " . str_pad("Email", 30) . " | Fecha de creación\n";
        echo str_repeat("-", 75) . "\n";
        
        foreach ($users as $user) {
            echo str_pad($user['id'], 5) . " | " . 
                 str_pad(substr($user['name'], 0, 18) . (strlen($user['name']) > 18 ? '...' : ''), 20) . " | " . 
                 str_pad(substr($user['email'], 0, 28) . (strlen($user['email']) > 28 ? '...' : ''), 30) . " | " . 
                 $user['created_at'] . "\n";
        }
    } else {
        echo "No se encontraron usuarios en el sistema.\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
