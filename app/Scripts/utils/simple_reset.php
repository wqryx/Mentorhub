<?php

$host = '127.0.0.1';
$dbname = 'mentorhub';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Eliminar relaciones de roles
    $conn->exec("DELETE FROM model_has_roles");
    
    // 2. Eliminar usuarios (excepto el usuario 1 si existe)
    $conn->exec("DELETE FROM users WHERE id > 0");
    
    // 3. Restablecer auto_increment
    $conn->exec("ALTER TABLE users AUTO_INCREMENT = 1");
    
    // 4. Insertar usuarios de prueba
    $users = [
        ['Admin User', 'admin@mentorhub.com', 'Admin123!', 'admin'],
        ['Mentor User', 'mentor@mentorhub.com', 'Mentor123!', 'mentor'],
        ['Student User', 'student@mentorhub.com', 'Student123!', 'student']
    ];
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) 
                          VALUES (?, ?, ?, NOW(), NOW(), NOW())");
    
    foreach ($users as $user) {
        $hashedPassword = password_hash($user[2], PASSWORD_DEFAULT);
        $stmt->execute([$user[0], $user[1], $hashedPassword]);
        $userId = $conn->lastInsertId();
        
        // Asignar rol
        $conn->exec("INSERT INTO model_has_roles (role_id, model_type, model_id) 
                    VALUES ((SELECT id FROM roles WHERE name = '$user[3]'), 'App\\\\Models\\\\User', $userId)");
        
        echo "Usuario creado: $user[0] ($user[1]) con contraseña: $user[2]\n";
    }
    
    echo "\n¡Usuarios restablecidos exitosamente!\n\n";
    
    // Mostrar usuarios creados
    $result = $conn->query("SELECT u.id, u.name, u.email, r.name as role 
                           FROM users u 
                           JOIN model_has_roles mhr ON u.id = mhr.model_id 
                           JOIN roles r ON mhr.role_id = r.id");
    
    echo "Usuarios actuales en el sistema:\n";
    echo str_repeat("-", 70) . "\n";
    echo str_pad("ID", 5) . " | " . str_pad("Nombre", 20) . " | " . 
         str_pad("Email", 25) . " | Rol\n";
    echo str_repeat("-", 70) . "\n";
    
    foreach ($result as $row) {
        echo str_pad($row['id'], 5) . " | " . 
             str_pad($row['name'], 20) . " | " . 
             str_pad($row['email'], 25) . " | " . 
             ucfirst($row['role']) . "\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
