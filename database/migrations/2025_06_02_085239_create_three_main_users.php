<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Asegurarse de que existan los roles correctos
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'mentor', 'guard_name' => 'web'],
            ['name' => 'student', 'guard_name' => 'web']
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                $role
            );
        }

        // Obtener IDs de roles
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $mentorRoleId = DB::table('roles')->where('name', 'mentor')->value('id');
        $studentRoleId = DB::table('roles')->where('name', 'student')->value('id');

        // 2. Eliminar usuarios existentes (si los hay)
        DB::table('model_has_roles')->whereIn('model_id', [1, 2, 3])->delete();
        DB::table('users')->whereIn('id', [1, 2, 3])->delete();

        // 3. Crear los 3 usuarios principales
        $users = [
            // Administrador
            [
                'id' => 1,
                'name' => 'Administrador',
                'email' => 'admin@mentorhub.com',
                'password' => Hash::make('Admin123!'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'role_id' => $adminRoleId
            ],
            // Mentor
            [
                'id' => 2,
                'name' => 'Mentor Principal',
                'email' => 'mentor@mentorhub.com',
                'password' => Hash::make('Mentor123!'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'role_id' => $mentorRoleId
            ],
            // Estudiante
            [
                'id' => 3,
                'name' => 'Estudiante Ejemplo',
                'email' => 'estudiante@mentorhub.com',
                'password' => Hash::make('Estudiante123!'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'role_id' => $studentRoleId
            ]
        ];

        // 4. Insertar usuarios
        foreach ($users as $user) {
            $roleId = $user['role_id'];
            unset($user['role_id']);

            // Insertar usuario con ID específico
            DB::table('users')->updateOrInsert(
                ['id' => $user['id']],
                $user
            );

            // Asignar rol
            DB::table('model_has_roles')->updateOrInsert(
                ['model_id' => $user['id'], 'model_type' => 'App\\Models\\User'],
                ['role_id' => $roleId]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminamos usuarios en el rollback para evitar pérdida de datos
    }
};
