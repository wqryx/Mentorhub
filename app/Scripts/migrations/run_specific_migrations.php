<?php

// Configuración
$host = '127.0.0.1';
$dbname = 'mentorhub';
$username = 'root';
$password = '';

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado a la base de datos '$dbname'.\n\n";
    
    // Tablas que necesitamos verificar/crear
    $tables = [
        'courses' => '2025_05_28_005505_create_courses_table',
        'event_user' => '2025_05_28_010404_create_event_user_table',
        'activities' => '2025_05_28_010724_create_activities_table',
        'course_user' => '2025_05_28_021056_create_course_user_table',
        'permissions' => '2025_05_28_030008_create_permission_tables',
        'roles' => '2025_05_28_051600_create_roles_and_user_roles_tables',
        'tutorials' => '2025_05_28_080527_create_tutorials_table',
        'modules' => '2025_05_28_080536_create_modules_table',
        'contents' => '2025_05_28_080545_create_contents_table',
        'forums' => '2025_05_28_080600_create_forums_table',
        'threads' => '2025_05_28_080608_create_threads_table',
        'posts' => '2025_05_28_080615_create_posts_table',
        'solutions' => '2025_05_28_080624_create_solutions_table',
        'mentor_profiles' => '2025_05_28_080633_create_mentor_profiles_table',
        'mentor_sessions' => '2025_05_28_080640_create_mentor_sessions_table',
        'mentor_reviews' => '2025_05_28_080647_create_mentor_reviews_table',
        'mentor_availabilities' => '2025_05_28_080654_create_mentor_availabilities_table',
        'user_profiles' => '2025_05_28_161950_create_user_profiles_table_fix',
        'specialities' => '2025_05_28_080714_create_specialities_table',
        'course_histories' => '2025_05_28_080714_create_course_histories_table'
    ];
    
    // Verificar y crear tablas faltantes
    foreach ($tables as $table => $migration) {
        echo "Verificando tabla '$table'... ";
        
        try {
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            
            if ($result->rowCount() === 0) {
                echo "No existe. Ejecutando migración... ";
                
                // Ejecutar la migración específica
                $output = [];
                $return_var = 0;
                exec("php artisan migrate:refresh --path=/database/migrations/$migration.php --force", $output, $return_var);
                
                if ($return_var === 0) {
                    echo "¡Éxito!\n";
                } else {
                    echo "Error al ejecutar la migración.\n";
                    print_r($output);
                }
            } else {
                echo "Ya existe.\n";
            }
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\nProceso completado.\n";
    
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage() . "\n");
}
