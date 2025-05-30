<?php

$host = '127.0.0.1';
$dbname = 'mentorhub';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();

    // 1. Primero eliminamos las relaciones de roles
    $conn->exec("DELETE FROM model_has_roles");
    
    // 2. Luego eliminamos los usuarios
    $conn->exec("DELETE FROM users WHERE id > 0");
    
    // 3. Restablecemos el auto_increment
    $conn->exec("ALTER TABLE users AUTO_INCREMENT = 1");
    
    // 4. Insertamos los nuevos usuarios
    $users = [
        [
            'name' => 'Admin User',
            'email' => 'admin@mentorhub.com',
            'password' => password_hash('Admin123!', PASSWORD_DEFAULT),
            'role' => 'admin'
        ],
        [
            'name' => 'Mentor User',
            'email' => 'mentor@mentorhub.com',
            'password' => password_hash('Mentor123!', PASSWORD_DEFAULT),
            'role' => 'mentor'
        ],
        [
            'name' => 'Student User',
            'email' => 'student@mentorhub.com',
            'password' => password_hash('Student123!', PASSWORD_DEFAULT),
            'role' => 'student'
        ]
    ];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) 
                          VALUES (:name, :email, :password, NOW(), NOW(), NOW())");
    
    $roleStmt = $conn->prepare("INSERT INTO model_has_roles (role_id, model_type, model_id) 
                               VALUES ((SELECT id FROM roles WHERE name = :role), 'App\\Models\\User', :user_id)");

    foreach ($users as $user) {
        // Insertar usuario
        $stmt->execute([
            ':name' => $user['name'],
            ':email' => $user['email'],
            ':password' => $user['password']
        ]);
        
        $userId = $conn->lastInsertId();
        
        // Asignar rol
        $roleStmt->execute([
            ':role' => $user['role'],
            ':user_id' => $userId
        ]);
        
        echo "Usuario creado: {$user['name']} ({$user['email']}) con contraseña: " . 
             str_replace('123!', '***', $user['password']) . "\n";
    }
    
    $conn->commit();
    echo "\n¡Usuarios restablecidos exitosamente!\n";
    
} catch(PDOException $e) {
    $conn->rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}

// Mostrar los usuarios creados
echo "\nUsuarios actuales en el sistema:\n";
$result = $conn->query("SELECT u.id, u.name, u.email, r.name as role 
                       FROM users u 
                       JOIN model_has_roles mhr ON u.id = mhr.model_id 
                       JOIN roles r ON mhr.role_id = r.id");

echo str_pad("ID", 5) . " | " . str_pad("Nombre", 20) . " | " . 
     str_pad("Email", 25) . " | Rol\n";
echo str_repeat("-", 60) . "\n";

foreach ($result as $row) {
    echo str_pad($row['id'], 5) . " | " . 
         str_pad($row['name'], 20) . " | " . 
         str_pad($row['email'], 25) . " | " . 
         ucfirst($row['role']) . "\n";
}
