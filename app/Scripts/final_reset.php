<?php

$host = '127.0.0.1';
$dbname = 'mentorhub';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Iniciando restablecimiento de usuarios...\n";
    
    // 1. Eliminar relaciones de roles
    echo "Eliminando relaciones de roles...\n";
    $conn->exec("DELETE FROM model_has_roles");
    
    // 2. Eliminar usuarios
    echo "Eliminando usuarios existentes...\n";
    $conn->exec("DELETE FROM users");
    
    // 3. Insertar usuarios de prueba
    echo "Creando nuevos usuarios...\n";
    
    $users = [
        ['Admin User', 'admin@mentorhub.com', 'Admin123!', 'admin'],
        ['Mentor User', 'mentor@mentorhub.com', 'Mentor123!', 'mentor'],
        ['Student User', 'student@mentorhub.com', 'Student123!', 'student']
    ];
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, created_at, updated_at) 
                          VALUES (?, ?, ?, NOW(), NOW())");
    
    foreach ($users as $user) {
        $hashedPassword = password_hash($user[2], PASSWORD_DEFAULT);
        $stmt->execute([$user[0], $user[1], $hashedPassword]);
        $userId = $conn->lastInsertId();
        
        // Asignar rol
        $roleStmt = $conn->prepare("INSERT INTO model_has_roles (role_id, model_type, model_id) 
                                  VALUES ((SELECT id FROM roles WHERE name = ?), 'App\\\\Models\\\\User', ?)");
        $roleStmt->execute([$user[3], $userId]);
        
        echo "- Usuario creado: $user[0] ($user[1])\n";
        echo "  Contraseña: $user[2]\n";
    }
    
    // Mostrar resumen
    echo "\n¡Usuarios restablecidos exitosamente!\n\n";
    
    echo "Credenciales de acceso:\n";
    echo str_repeat("=", 50) . "\n";
    echo "ADMINISTRADOR\n";
    echo "Email: admin@mentorhub.com\n";
    echo "Contraseña: Admin123!\n\n";
    
    echo "MENTOR\n";
    echo "Email: mentor@mentorhub.com\n";
    echo "Contraseña: Mentor123!\n\n";
    
    echo "ESTUDIANTE\n";
    echo "Email: student@mentorhub.com\n";
    echo "Contraseña: Student123!\n";
    echo str_repeat("=", 50) . "\n";
    
} catch(PDOException $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    echo "Código de error: " . $e->getCode() . "\n";
}
