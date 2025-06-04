<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BasicRolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Deshabilitar verificación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpiar tablas
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        
        // Crear roles básicos
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'mentor', 'guard_name' => 'web'],
            ['name' => 'student', 'guard_name' => 'web']
        ];
        
        foreach ($roles as $role) {
            if (!DB::table('roles')->where('name', $role['name'])->where('guard_name', $role['guard_name'])->exists()) {
                DB::table('roles')->insert($role);
            }
        }
        
        // Crear permisos básicos
        $permissions = [
            // Permisos de administración
            ['name' => 'view admin dashboard', 'guard_name' => 'web'],
            ['name' => 'manage users', 'guard_name' => 'web'],
            ['name' => 'manage roles', 'guard_name' => 'web'],
            ['name' => 'manage permissions', 'guard_name' => 'web'],
            
            // Permisos de mentor
            ['name' => 'view mentor dashboard', 'guard_name' => 'web'],
            ['name' => 'manage courses', 'guard_name' => 'web'],
            
            // Permisos de estudiante
            ['name' => 'view student dashboard', 'guard_name' => 'web'],
            ['name' => 'enroll courses', 'guard_name' => 'web'],
        ];
        
        foreach ($permissions as $permission) {
            if (!DB::table('permissions')->where('name', $permission['name'])->exists()) {
                DB::table('permissions')->insert($permission);
            }
        }
        
        // Asignar todos los permisos al rol de administrador
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        $permissions = DB::table('permissions')->pluck('id');
        
        foreach ($permissions as $permissionId) {
            if (!DB::table('role_has_permissions')
                ->where('role_id', $adminRole->id)
                ->where('permission_id', $permissionId)
                ->exists()) {
                
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $adminRole->id
                ]);
            }
        }
        
        // Asignar permisos al rol de mentor
        $mentorRole = DB::table('roles')->where('name', 'mentor')->first();
        $mentorPermissions = DB::table('permissions')
            ->whereIn('name', [
                'view mentor dashboard',
                'manage courses',
                'enroll courses'
            ])
            ->pluck('id');
            
        foreach ($mentorPermissions as $permissionId) {
            if (!DB::table('role_has_permissions')
                ->where('role_id', $mentorRole->id)
                ->where('permission_id', $permissionId)
                ->exists()) {
                
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $mentorRole->id
                ]);
            }
        }
        
        // Asignar permisos al rol de estudiante
        $studentRole = DB::table('roles')->where('name', 'student')->first();
        $studentPermissions = DB::table('permissions')
            ->whereIn('name', [
                'view student dashboard',
                'enroll courses'
            ])
            ->pluck('id');
            
        foreach ($studentPermissions as $permissionId) {
            if (!DB::table('role_has_permissions')
                ->where('role_id', $studentRole->id)
                ->where('permission_id', $permissionId)
                ->exists()) {
                
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $studentRole->id
                ]);
            }
        }
        
        // Crear un usuario administrador si no existe
        if (!DB::table('users')->where('email', 'admin@mentorhub.test')->exists()) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Administrador',
                'email' => 'admin@mentorhub.test',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Asignar rol de administrador
            DB::table('model_has_roles')->insert([
                'role_id' => $adminRole->id,
                'model_type' => 'App\\Models\\User',
                'model_id' => $userId
            ]);
        }
        
        // Reactivar verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Roles y permisos básicos creados exitosamente.');
        $this->command->info('Usuario administrador creado:');
        $this->command->info('Email: admin@mentorhub.test');
        $this->command->info('Contraseña: password');
    }
}
