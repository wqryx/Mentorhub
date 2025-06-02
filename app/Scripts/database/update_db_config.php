<?php

// Ruta al archivo .env
$envFile = __DIR__ . '/.env';

// Verificar si el archivo .env existe
if (!file_exists($envFile)) {
    die("Error: El archivo .env no existe en " . $envFile);
}

// Leer el contenido actual del archivo .env
$content = file_get_contents($envFile);

// Actualizar el nombre de la base de datos a 'mentohub'
$newContent = preg_replace(
    '/DB_DATABASE=.*/',
    'DB_DATABASE=mentohub',
    $content
);

// Guardar los cambios
if (file_put_contents($envFile, $newContent) !== false) {
    echo "✓ Se ha actualizado la configuración de la base de datos a 'mentohub'\n";
} else {
    die("✗ Error al guardar los cambios en el archivo .env");
}

// Verificar si la base de datos existe y crearla si es necesario
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar si la base de datos existe
    $result = $pdo->query("SHOW DATABASES LIKE 'mentohub'");
    
    if ($result->rowCount() === 0) {
        // Crear la base de datos si no existe
        $pdo->exec("CREATE DATABASE mentohub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ Base de datos 'mentohub' creada exitosamente\n";
        
        // Ejecutar migraciones
        echo "\nEjecutando migraciones...\n";
        exec('php artisan migrate --force', $output, $returnVar);
        
        if ($returnVar === 0) {
            echo "✓ Migraciones ejecutadas correctamente\n";
            
            // Crear usuarios de prueba
            echo "\nCreando usuarios de prueba...\n";
            require_once 'create_test_users.php';
        } else {
            echo "✗ Error al ejecutar las migraciones. Intenta ejecutar manualmente: php artisan migrate --force\n";
        }
    } else {
        echo "✓ La base de datos 'mentohub' ya existe\n";
        echo "\nPara ejecutar las migraciones, usa: php artisan migrate --force\n";
    }
    
} catch (PDOException $e) {
    die("✗ Error de conexión a MySQL: " . $e->getMessage() . "\n");
}
