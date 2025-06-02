<?php

$envFile = __DIR__.'/.env';

// Verificar si el archivo .env existe
if (!file_exists($envFile)) {
    die("Error: El archivo .env no existe.\n");
}

// Leer el contenido actual
echo "Leyendo archivo .env...\n";
$content = file_get_contents($envFile);

// Verificar si hay que hacer cambios
$changes = 0;

// Corregir el nombre de la base de datos
if (strpos($content, 'DB_DATABASE=mentohub') !== false) {
    $content = str_replace('DB_DATABASE=mentohub', 'DB_DATABASE=mentorhub', $content, $count);
    $changes += $count;
    echo "- Corregido nombre de la base de datos a 'mentorhub'\n";
}

// Guardar los cambios si son necesarios
if ($changes > 0) {
    if (file_put_contents($envFile, $content) !== false) {
        echo "\n¡Se realizaron $changes cambios en el archivo .env!\n";
        
        // Mostrar la nueva configuración
        echo "\nNueva configuración de la base de datos:\n";
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, 'DB_') === 0) {
                echo "$line\n";
            }
        }
        
        echo "\nPor favor, limpia la caché de la aplicación ejecutando:\n";
        echo "1. php artisan config:clear\n";
        echo "2. php artisan cache:clear\n";
        echo "3. php artisan view:clear\n";
    } else {
        echo "\nError: No se pudo guardar el archivo .env. Verifica los permisos.\n";
    }
} else {
    echo "\nNo se encontró 'mentohub' en la configuración de la base de datos.\n";
    echo "La configuración actual parece estar correcta.\n";
}

echo "\nProceso completado.\n";
