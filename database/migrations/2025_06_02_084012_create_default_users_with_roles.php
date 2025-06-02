<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Asegurarse de que existan los roles correctos
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $mentorRoleId = DB::table('roles')->where('name', 'mentor')->value('id');
        $studentRoleId = DB::table('roles')->where('name', 'student')->value('id');
        
        if (!$adminRoleId || !$mentorRoleId || !$studentRoleId) {
            // Si algún rol no existe, crear los roles básicos
            if (!$adminRoleId) {
                $adminRoleId = DB::table('roles')->insertGetId([
                    'name' => 'admin',
                    'guard_name' => 'web',
                    'description' => 'Administrador del sistema con acceso completo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            if (!$mentorRoleId) {
                $mentorRoleId = DB::table('roles')->insertGetId([
                    'name' => 'mentor',
                    'guard_name' => 'web',
                    'description' => 'Mentor/Profesor que puede crear cursos y evaluar estudiantes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            if (!$studentRoleId) {
                $studentRoleId = DB::table('roles')->insertGetId([
                    'name' => 'student',
                    'guard_name' => 'web',
                    'description' => 'Estudiante que puede inscribirse en cursos',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // 2. Crear usuarios predeterminados si no existen
        $users = [
            // Administrador
            [
                'name' => 'Administrador',
                'email' => 'admin@mentorhub.com',
                'password' => Hash::make('Admin123!'),
                'role_id' => $adminRoleId,
                'email_verified_at' => now(),
            ],
            // Mentor
            [
                'name' => 'Mentor Ejemplo',
                'email' => 'mentor@mentorhub.com',
                'password' => Hash::make('Mentor123!'),
                'role_id' => $mentorRoleId,
                'email_verified_at' => now(),
            ],
            // Estudiante
            [
                'name' => 'Estudiante Ejemplo',
                'email' => 'estudiante@mentorhub.com',
                'password' => Hash::make('Estudiante123!'),
                'role_id' => $studentRoleId,
                'email_verified_at' => now(),
            ],
        ];
        
        // Crear usuarios adicionales de prueba
        // 5 mentores adicionales
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'name' => "Mentor {$i}",
                'email' => "mentor{$i}@mentorhub.com",
                'password' => Hash::make("Mentor{$i}123!"),
                'role_id' => $mentorRoleId,
                'email_verified_at' => now(),
            ];
        }
        
        // 10 estudiantes adicionales
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'name' => "Estudiante {$i}",
                'email' => "estudiante{$i}@mentorhub.com",
                'password' => Hash::make("Estudiante{$i}123!"),
                'role_id' => $studentRoleId,
                'email_verified_at' => now(),
            ];
        }
        
        // Insertar usuarios
        foreach ($users as $userData) {
            $roleId = $userData['role_id'];
            unset($userData['role_id']);
            
            // Verificar si el usuario ya existe
            $existingUser = DB::table('users')->where('email', $userData['email'])->first();
            
            if (!$existingUser) {
                // Crear el usuario
                $userId = DB::table('users')->insertGetId([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                    'email_verified_at' => $userData['email_verified_at'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Asignar rol al usuario
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId
                ]);
                
                // No podemos usar $this->command en migraciones
                // Información del usuario creado: {$userData['email']} - Contraseña visible en el código
            } else {
                // Actualizar el rol del usuario existente
                DB::table('model_has_roles')
                    ->where('model_id', $existingUser->id)
                    ->where('model_type', 'App\\Models\\User')
                    ->delete();
                
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $existingUser->id
                ]);
                
                // Usuario actualizado: {$userData['email']}
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminamos usuarios en el rollback para evitar pérdida de datos
        // Esta migración no crea tablas, solo inserta datos
    }
};
