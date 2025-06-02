<?php

$envFile = __DIR__ . '/.env';
$envContent = file_get_contents($envFile);

// Actualizar la configuración de la base de datos
$envContent = preg_replace(
    '/^DB_DATABASE=.*/m',
    'DB_DATABASE=x',
    $envContent
);

file_put_contents($envFile, $envContent);

echo "Configuración de la base de datos actualizada correctamente.\n";
