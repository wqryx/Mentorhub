<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// 1. Limpiar la base de datos
echo "Paso 1: Limpiando la base de datos...\n";

try {
    // Desactivar la verificación de claves foráneas temporalmente
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    // Obtener todas las tablas
    $tables = DB::select('SHOW TABLES');
    
    // Eliminar todas las tablas
    foreach ($tables as $table) {
        $tableName = $table->{array_key_first((array)$table)};
        if ($tableName !== 'migrations') {
            DB::statement("DROP TABLE IF EXISTS `$tableName`");
            echo "- Tabla eliminada: $tableName\n";
        }
    }
    
    // Vaciar la tabla de migraciones
    DB::table('migrations')->truncate();
    
    // Reactivar la verificación de claves foráneas
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    echo "✅ Base de datos limpiada correctamente\n\n";
} catch (\Exception $e) {
    echo "❌ Error al limpiar la base de datos: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Ejecutar migraciones en orden específico
echo "Paso 2: Ejecutando migraciones en orden específico...\n";

// Orden de migraciones para evitar problemas de dependencias
$migrations = [
    // Migraciones básicas de Laravel
    '2014_10_12_000000_create_users_table',
    '2014_10_12_100000_create_password_reset_tokens_table',
    '2019_08_19_000000_create_failed_jobs_table',
    '2019_12_14_000001_create_personal_access_tokens_table',
    '2025_05_19_150720_create_sessions_table',
    
    // Tablas principales
    '2025_05_28_005505_create_courses_table',
    '2025_05_28_000000_create_enrollments_table',  // Nuestra migración corregida
    '2025_05_28_080600_create_forums_table',
    '2025_05_28_080608_create_threads_table',
    '2025_05_28_080640_create_mentor_sessions_table',
    '2025_05_28_080647_create_mentor_reviews_table',
    '2025_05_28_080654_create_mentor_availabilities_table',
    '2025_05_28_162600_create_permission_tables',
    '2025_05_28_080700_create_course_histories_table',  // Después de crear las tablas relacionadas
    '2025_05_29_171406_add_progress_columns_to_enrollments_table',
    '2025_05_28_080846_update_enrollments_table_for_courses',
    '2023_05_30_000000_create_messages_table',
    '2024_05_30_154500_add_role_to_users_table',
    '2025_05_30_000000_create_activity_logs_table',
    '2025_06_02_080539_create_cache_table',
];

foreach ($migrations as $migration) {
    $migrationFile = "database/migrations/{$migration}.php";
    echo "Migrando: $migration\n";
    
    if (file_exists($migrationFile)) {
        try {
            $exitCode = Artisan::call('migrate', [
                '--path' => $migrationFile,
                '--force' => true,
            ]);
            
            if ($exitCode === 0) {
                echo "✅ Migración exitosa: $migration\n";
            } else {
                echo "❌ Error en migración: $migration\n";
                echo Artisan::output() . "\n";
                // Continuar con la siguiente migración en lugar de salir
            }
        } catch (\Exception $e) {
            echo "❌ Excepción en migración $migration: " . $e->getMessage() . "\n";
            // Continuar con la siguiente migración en lugar de salir
        }
    } else {
        echo "⚠️ Archivo de migración no encontrado: $migrationFile\n";
    }
}

// 3. Crear roles y permisos
echo "\nPaso 3: Configurando roles y permisos...\n";
try {
    // Verificar si la tabla de roles existe
    if (Schema::hasTable('roles')) {
        // Crear roles si no existen
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $mentorRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'mentor']);
        $studentRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'student']);
        
        echo "✅ Roles configurados correctamente\n";
    } else {
        echo "⚠️ La tabla de roles no existe. Asegúrate de que la migración de permisos se haya ejecutado correctamente.\n";
    }
} catch (\Exception $e) {
    echo "❌ Error al configurar roles: " . $e->getMessage() . "\n";
}

// 4. Crear usuario administrador
echo "\nPaso 4: Creando usuario administrador...\n";
try {
    // Verificar si el usuario admin ya existe
    $admin = \App\Models\User::where('email', 'admin@mentorhub.test')->first();
    
    if (!$admin) {
        $admin = new \App\Models\User([
            'name' => 'Administrador',
            'email' => 'admin@mentorhub.test',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $admin->save();
        echo "✅ Usuario administrador creado\n";
    } else {
        $admin->password = \Illuminate\Support\Facades\Hash::make('password');
        $admin->save();
        echo "✅ Usuario administrador actualizado\n";
    }
    
    // Asignar rol de administrador
    $admin->assignRole('admin');
    echo "✅ Rol de administrador asignado\n";
    
    echo "\n🔑 Credenciales de administrador:\n";
    echo "   Email: admin@mentorhub.test\n";
    echo "   Contraseña: password\n";
} catch (\Exception $e) {
    echo "❌ Error al crear usuario administrador: " . $e->getMessage() . "\n";
}

// 5. Verificación final
echo "\n✅ Proceso de migración completado\n\n";

// Mostrar tablas creadas
$tables = DB::select('SHOW TABLES');
echo "Tablas en la base de datos:\n";
foreach ($tables as $table) {
    echo "- " . $table->{array_key_first((array)$table)} . "\n";
}

echo "\n¡La base de datos ha sido configurada correctamente! 🚀\n";
