<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

// Configurar caché en memoria
config(['cache.default' => 'array']);
config(['queue.default' => 'sync']);

// Ejecutar migraciones
$migrations = [
    '2025_05_28_162600_create_permission_tables.php',
    '2025_05_28_162900_setup_roles_and_permissions.php',
    '2025_05_28_080600_create_forums_table.php',
    '2025_05_28_080608_create_threads_table.php',
    '2025_05_28_080640_create_mentor_sessions_table.php',
    '2025_05_28_080647_create_mentor_reviews_table.php',
    '2025_05_28_080654_create_mentor_availabilities_table.php',
    '2025_05_28_080714_create_course_histories_table.php',
    '2025_05_28_080846_update_enrollments_table_for_courses.php',
    '2025_05_29_171406_add_progress_columns_to_enrollments_table.php',
    '2025_05_30_000001_create_mentorship_sessions_table.php',
    '2025_05_30_000002_create_mentorship_reviews_table.php',
    '2023_05_30_000000_create_activity_logs_table.php',
    '2023_05_30_create_messages_table.php',
    '2024_05_30_154500_add_role_to_users_table.php'
];

foreach ($migrations as $migration) {
    $path = __DIR__.'/../../database/migrations/' . $migration;
    if (file_exists($path)) {
        echo "Ejecutando migración: $migration\n";
        $output = [];
        $exitCode = Artisan::call('migrate', [
            '--path' => 'database/migrations/' . $migration,
            '--force' => true,
        ]);
        
        if ($exitCode === 0) {
            echo "✅ Migración exitosa: $migration\n";
        } else {
            echo "❌ Error en migración: $migration\n";
            echo Artisan::output() . "\n";
        }
    } else {
        echo "⚠️  Archivo no encontrado: $migration\n";
    }
}

echo "\n¡Proceso de migración completado!\n";
