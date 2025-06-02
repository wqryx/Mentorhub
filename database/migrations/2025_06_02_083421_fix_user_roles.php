<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Corregir los nombres de los roles en la tabla roles para que coincidan con los usados en el código
        DB::table('roles')
            ->where('name', 'Estudiante')
            ->update(['name' => 'student']);
            
        DB::table('roles')
            ->where('name', 'Mentor')
            ->update(['name' => 'mentor']);
            
        DB::table('roles')
            ->where('name', 'Admin')
            ->update(['name' => 'admin']);
        
        // 2. Asignar roles correctos a los usuarios según su tipo
        // Obtener IDs de roles
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
        
        // 3. Limpiar asignaciones de roles existentes para evitar duplicados
        DB::table('model_has_roles')->where('model_type', 'App\\Models\\User')->delete();
        
        // 4. Asignar roles según el tipo de usuario (basado en el email o algún otro criterio)
        // Identificar administradores (por ejemplo, por dominio de correo o por IDs específicos)
        $adminEmails = ['admin@mentorhub.com', 'admin@example.com'];
        $adminUsers = DB::table('users')
            ->whereIn('email', $adminEmails)
            ->get(['id']);
            
        foreach ($adminUsers as $user) {
            DB::table('model_has_roles')->insert([
                'role_id' => $adminRoleId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id
            ]);
        }
        
        // Identificar mentores (por ejemplo, por dominio de correo o por IDs específicos)
        $mentorEmails = ['mentor@mentorhub.com', 'mentor@example.com'];
        $mentorUsers = DB::table('users')
            ->whereIn('email', $mentorEmails)
            ->get(['id']);
            
        foreach ($mentorUsers as $user) {
            DB::table('model_has_roles')->insert([
                'role_id' => $mentorRoleId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id
            ]);
        }
        
        // Asignar rol de estudiante a los usuarios restantes
        $assignedUserIds = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->pluck('model_id')
            ->toArray();
            
        $remainingUsers = DB::table('users')
            ->whereNotIn('id', $assignedUserIds)
            ->get(['id']);
            
        foreach ($remainingUsers as $user) {
            DB::table('model_has_roles')->insert([
                'role_id' => $studentRoleId,
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los nombres de los roles a su estado original
        DB::table('roles')
            ->where('name', 'student')
            ->update(['name' => 'Estudiante']);
            
        DB::table('roles')
            ->where('name', 'mentor')
            ->update(['name' => 'Mentor']);
            
        DB::table('roles')
            ->where('name', 'admin')
            ->update(['name' => 'Admin']);
    }
};
