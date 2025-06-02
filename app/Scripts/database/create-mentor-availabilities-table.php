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
    
    // Verificar si la tabla mentor_availabilities existe
    $tableExists = $pdo->query("SHOW TABLES LIKE 'mentor_availabilities'")->rowCount() > 0;
    
    if (!$tableExists) {
        echo "Creando tabla 'mentor_availabilities'...\n";
        
        $sql = "CREATE TABLE `mentor_availabilities` (
            `id` bigint(20) UNSIGNED NOT NULL,
            `mentor_id` bigint(20) UNSIGNED NOT NULL,
            `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
            `start_time` time NOT NULL,
            `end_time` time NOT NULL,
            `is_available` tinyint(1) NOT NULL DEFAULT '1',
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
          
          ALTER TABLE `mentor_availabilities`
            ADD PRIMARY KEY (`id`),
            ADD KEY `mentor_availabilities_mentor_id_foreign` (`mentor_id`);
            
          ALTER TABLE `mentor_availabilities`
            MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
            
          ALTER TABLE `mentor_availabilities`
            ADD CONSTRAINT `mentor_availabilities_mentor_id_foreign` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;";
        
        $pdo->exec($sql);
        echo "✅ Tabla 'mentor_availabilities' creada exitosamente.\n";
    } else {
        echo "ℹ️  La tabla 'mentor_availabilities' ya existe.\n";
    }
    
    // Verificar si la tabla ya tiene datos
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM mentor_availabilities");
    $count = $stmt->fetch()['count'];
    
    if ($count == 0) {
        echo "La tabla 'mentor_availabilities' está vacía.\n";
    } else {
        echo "La tabla 'mentor_availabilities' ya contiene $count registros.\n";
    }
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
