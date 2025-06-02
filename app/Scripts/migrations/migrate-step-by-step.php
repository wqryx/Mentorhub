<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Configurar caché en memoria
config(['cache.default' => 'array']);
config(['queue.default' => 'sync']);

echo "Iniciando migración paso a paso...\n\n";

// Paso 1: Limpiar la base de datos
try {
    echo "Paso 1: Limpiando la base de datos...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        if ($tableName != 'migrations') {
            Schema::dropIfExists($tableName);
            echo "- Tabla eliminada: $tableName\n";
        }
    }
    
    DB::table('migrations')->truncate();
    echo "- Tabla migrations limpiada\n";
    
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    echo "✅ Base de datos limpiada correctamente\n\n";
} catch (\Exception $e) {
    echo "❌ Error al limpiar la base de datos: " . $e->getMessage() . "\n\n";
}

// Paso 2: Ejecutar migraciones en orden específico
echo "Paso 2: Ejecutando migraciones en orden específico...\n";

// Orden de migraciones para evitar problemas de dependencias
$migrations = [
    // Primero las tablas base
    '2014_10_12_000000_create_users_table',
    '2014_10_12_100000_create_password_reset_tokens_table',
    '2019_08_19_000000_create_failed_jobs_table',
    '2019_12_14_000001_create_personal_access_tokens_table',
    
    // Tablas de permisos y roles
    '2025_05_28_162600_create_permission_tables',
    '2025_05_28_162900_setup_roles_and_permissions',
    
    // Tablas relacionadas con cursos
    '2025_05_28_005505_create_courses_table',
    '2025_05_28_021056_create_course_user_table',
    '2025_05_28_080714_create_course_histories_table',
    '2025_05_28_080846_update_enrollments_table_for_courses',
    '2025_05_29_171406_add_progress_columns_to_enrollments_table',
    
    // Tablas de perfiles y usuarios
    '2025_05_28_161950_create_user_profiles_table_fix',
    '2024_05_30_154500_add_role_to_users_table',
    
    // Tablas de mentores
    '2025_05_28_080633_create_mentor_profiles_table',
    '2025_05_28_080640_create_mentor_sessions_table',
    '2025_05_28_080647_create_mentor_reviews_table',
    '2025_05_28_080654_create_mentor_availabilities_table',
    
    // Tablas de foros
    '2025_05_28_080600_create_forums_table',
    '2025_05_28_080608_create_threads_table',
    
    // Otras tablas
    '2023_05_30_000000_create_messages_table',
    '2025_05_19_150720_create_sessions_table',
    '2025_05_30_000000_create_activity_logs_table'
];

foreach ($migrations as $migration) {
    echo "Migrando: $migration\n";
    try {
        // Buscar el archivo de migración exacto
        $migrationFile = "database/migrations/{$migration}.php";
        
        if (file_exists($migrationFile)) {
            $exitCode = Artisan::call('migrate', [
                '--path' => $migrationFile,
                '--force' => true,
            ]);
        
            if ($exitCode === 0) {
                echo "✅ Migración exitosa: $migration\n";
            } else {
                echo "❌ Error en migración: $migration\n";
                echo Artisan::output() . "\n";
            }
        } else {
            echo "⚠️ Archivo de migración no encontrado: $migrationFile\n";
        }
    } catch (\Exception $e) {
        echo "❌ Excepción en migración: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Paso 3: Crear usuario administrador
echo "Paso 3: Creando usuario administrador...\n";

try {
    // Verificar si existe el usuario admin
    $adminExists = DB::table('users')->where('email', 'admin@mentorhub.test')->exists();
    
    if (!$adminExists) {
        $userId = DB::table('users')->insertGetId([
            'name' => 'Administrador',
            'email' => 'admin@mentorhub.test',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Asignar rol de administrador
        if (Schema::hasTable('roles') && Schema::hasTable('model_has_roles')) {
            $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
            if ($adminRoleId) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $adminRoleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId
                ]);
                echo "✅ Usuario administrador creado y rol asignado\n";
                echo "   Email: admin@mentorhub.test\n";
                echo "   Contraseña: password\n";
            } else {
                echo "❌ No se encontró el rol 'admin'\n";
            }
        } else {
            echo "❌ Las tablas de roles no existen aún\n";
        }
    } else {
        echo "✅ El usuario administrador ya existe\n";
    }
} catch (\Exception $e) {
    echo "❌ Error al crear usuario administrador: " . $e->getMessage() . "\n";
}

echo "\n¡Proceso de migración completado!\n";

// Mostrar tablas creadas
try {
    $tables = DB::select('SHOW TABLES');
    echo "\nTablas en la base de datos:\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- $tableName\n";
    }
} catch (\Exception $e) {
    echo "Error al listar tablas: " . $e->getMessage() . "\n";
}
