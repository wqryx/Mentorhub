<?php

$host = '127.0.0.1';
$port = 3306;
$dbname = 'app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host:$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT u.*, GROUP_CONCAT(r.name) as roles 
                         FROM users u 
                         LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id 
                         LEFT JOIN roles r ON mhr.role_id = r.id 
                         WHERE mhr.model_type = 'App\\Models\\User' 
                         GROUP BY u.id");
    
    echo "<h1>Usuarios en la Base de Datos</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Roles</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['roles'] ?? 'Sin roles') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
