<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el rol de administrador si no existe
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        
        // Crear permisos básicos
        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
            'manage courses',
            'manage events',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        
        // Asignar todos los permisos al rol de administrador
        $adminRole->syncPermissions(Permission::all());
        
        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@mentorhub.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // Asignar el rol de administrador al usuario
        $admin->assignRole('Admin');
    }
}
