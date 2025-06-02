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
    
    // Verificar si la tabla users existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() === 0) {
        die("La tabla 'users' no existe en la base de datos.\n");
    }
    
    // Obtener la estructura de la tabla users
    echo "Estructura de la tabla 'users':\n";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Mostrar usuarios existentes
    echo "\nUsuarios en la base de datos:\n";
    $users = $pdo->query("SELECT id, name, email, created_at FROM users")->fetchAll();
    
    if (empty($users)) {
        echo "No hay usuarios en la base de datos.\n";
    } else {
        foreach ($users as $user) {
            echo "- ID: {$user['id']}, Nombre: {$user['name']}, Email: {$user['email']}, Creado: {$user['created_at']}\n";
        }
    }
    
    // Verificar roles
    echo "\nRoles en la base de datos:\n";
    $roles = $pdo->query("SELECT * FROM roles")->fetchAll();
    
    if (empty($roles)) {
        echo "No hay roles definidos.\n";
    } else {
        foreach ($roles as $role) {
            echo "- ID: {$role['id']}, Nombre: {$role['name']}\n";
        }
    }
    
    // Verificar asignación de roles a usuarios
    if (!empty($users)) {
        echo "\nAsignación de roles a usuarios:\n";
        foreach ($users as $user) {
            $stmt = $pdo->prepare("
                SELECT r.name 
                FROM roles r
                JOIN model_has_roles mhr ON r.id = mhr.role_id
                WHERE mhr.model_id = ? AND mhr.model_type = 'App\\\\Models\\\\User'
            ");
            $stmt->execute([$user['id']]);
            $userRoles = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            echo "Usuario: {$user['name']} ({$user['email']}) - Roles: " . implode(', ', $userRoles ?: ['Ninguno']) . "\n";
        }
    }
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
