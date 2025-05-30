<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SpatieRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $mentorRole = Role::firstOrCreate(['name' => 'Mentor', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'Estudiante', 'guard_name' => 'web']);
        $guestRole = Role::firstOrCreate(['name' => 'Guest', 'guard_name' => 'web']);

        // Crear permisos básicos
        $permissions = [
            // Permisos de administración
            'view admin dashboard',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage settings',
            
            // Permisos de cursos
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            'publish courses',
            
            // Permisos de mentoría
            'mentor students',
            'schedule sessions',
            'manage content',
            
            // Permisos de estudiante
            'enroll courses',
            'access premium content',
            'submit assignments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Asignar todos los permisos al rol Admin
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos a Mentor
        $mentorPermissions = [
            'view courses',
            'create courses',
            'edit courses',
            'mentor students',
            'schedule sessions',
            'manage content'
        ];
        $mentorRole->givePermissionTo($mentorPermissions);

        // Asignar permisos a Estudiante
        $studentPermissions = [
            'view courses',
            'enroll courses',
            'access premium content',
            'submit assignments'
        ];
        $studentRole->givePermissionTo($studentPermissions);

        // El rol Guest no tiene permisos por defecto
    }
}
