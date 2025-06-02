<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select('SHOW TABLES');
    echo "Conexión exitosa. Tablas en la base de datos:\n";
    foreach ($tables as $table) {
        $tableName = $table->{'Tables_in_' . env('DB_DATABASE')};
        echo "- $tableName\n";
    }
} catch (Exception $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage() . "\n";
}
