<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar las tablas
$tables = DB::select('SHOW TABLES');
$tableName = 'Tables_in_' . config('database.connections.mysql.database');

echo "Base de datos configurada: " . config('database.connections.mysql.database') . "\n\n";
echo "Lista de tablas en la base de datos (" . count($tables) . " tablas):\n";

foreach ($tables as $table) {
    echo "- " . $table->$tableName . "\n";
}

// Sugerir cómo completar la migración
echo "\nSi faltan tablas, puedes ejecutar las migraciones con:\n";
echo "php artisan migrate\n";
echo "\nO si prefieres reiniciar toda la base de datos:\n";
echo "php artisan migrate:fresh --seed\n";
