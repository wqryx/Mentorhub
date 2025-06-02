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
    
    // Deshabilitar verificación de claves foráneas temporalmente
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    
    // 1. Crear roles básicos
    echo "Creando roles...\n";
    $roles = [
        ['name' => 'admin', 'guard_name' => 'web'],
        ['name' => 'mentor', 'guard_name' => 'web'],
        ['name' => 'student', 'guard_name' => 'web']
    ];
    
    foreach ($roles as $role) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO roles (name, guard_name) VALUES (?, ?)");
        $stmt->execute([$role['name'], $role['guard_name']]);
        echo "- Rol creado: {$role['name']}\n";
    }
    
    // 2. Crear permisos básicos
    echo "\nCreando permisos...\n";
    $permissions = [
        // Permisos de administración
        ['name' => 'view admin dashboard', 'guard_name' => 'web'],
        ['name' => 'manage users', 'guard_name' => 'web'],
        ['name' => 'manage roles', 'guard_name' => 'web'],
        ['name' => 'manage permissions', 'guard_name' => 'web'],
        
        // Permisos de mentor
        ['name' => 'view mentor dashboard', 'guard_name' => 'web'],
        ['name' => 'manage courses', 'guard_name' => 'web'],
        
        // Permisos de estudiante
        ['name' => 'view student dashboard', 'guard_name' => 'web'],
        ['name' => 'enroll courses', 'guard_name' => 'web'],
    ];
    
    foreach ($permissions as $permission) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO permissions (name, guard_name) VALUES (?, ?)");
        $stmt->execute([$permission['name'], $permission['guard_name']]);
        echo "- Permiso creado: {$permission['name']}\n";
    }
    
    // 3. Asignar permisos a roles
    echo "\nAsignando permisos a roles...\n";
    
    // Obtener roles
    $adminRole = $pdo->query("SELECT id FROM roles WHERE name = 'admin' LIMIT 1")->fetch();
    $mentorRole = $pdo->query("SELECT id FROM roles WHERE name = 'mentor' LIMIT 1")->fetch();
    $studentRole = $pdo->query("SELECT id FROM roles WHERE name = 'student' LIMIT 1")->fetch();
    
    // Obtener permisos
    $allPermissions = $pdo->query("SELECT id, name FROM permissions")->fetchAll();
    $mentorPermissions = $pdo->query("SELECT id FROM permissions WHERE name IN ('view mentor dashboard', 'manage courses', 'enroll courses')")->fetchAll(PDO::FETCH_COLUMN);
    $studentPermissions = $pdo->query("SELECT id FROM permissions WHERE name IN ('view student dashboard', 'enroll courses')")->fetchAll(PDO::FETCH_COLUMN);
    
    // Asignar todos los permisos al admin
    foreach ($allPermissions as $permission) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO role_has_permissions (permission_id, role_id) VALUES (?, ?)");
        $stmt->execute([$permission['id'], $adminRole['id']]);
    }
    echo "- Todos los permisos asignados al rol admin\n";
    
    // Asignar permisos a mentor
    foreach ($mentorPermissions as $permissionId) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO role_has_permissions (permission_id, role_id) VALUES (?, ?)");
        $stmt->execute([$permissionId, $mentorRole['id']]);
    }
    echo "- Permisos asignados al rol mentor\n";
    
    // Asignar permisos a estudiante
    foreach ($studentPermissions as $permissionId) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO role_has_permissions (permission_id, role_id) VALUES (?, ?)");
        $stmt->execute([$permissionId, $studentRole['id']]);
    }
    echo "- Permisos asignados al rol estudiante\n";
    
    // 4. Crear usuario administrador
    echo "\nCreando usuario administrador...\n";
    
    $adminEmail = 'admin@mentorhub.test';
    $adminPassword = password_hash('password', PASSWORD_DEFAULT);
    $adminName = 'Administrador';
    
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);
    $adminUser = $stmt->fetch();
    
    if (!$adminUser) {
        // Crear usuario
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$adminName, $adminEmail, $adminPassword]);
        $userId = $pdo->lastInsertId();
        
        // Asignar rol de administrador
        $stmt = $pdo->prepare("INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES (?, ?, ?)");
        $stmt->execute([$adminRole['id'], 'App\\Models\\User', $userId]);
        
        echo "Usuario administrador creado exitosamente.\n";
        echo "Email: $adminEmail\n";
        echo "Contraseña: password\n";
    } else {
        echo "El usuario administrador ya existe.\n";
    }
    
    // Reactivar verificación de claves foráneas
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    
    echo "\n¡Configuración de base de datos completada!\n";
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
