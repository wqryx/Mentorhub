<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el rol de administrador si no existe
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web'
        ]);

        // Crear el usuario administrador si no existe
        $adminUser = User::firstOrCreate([
            'email' => 'admin@mentorhub.com'
        ], [
            'name' => 'Administrador Principal',
            'password' => Hash::make('admin123')
        ]);

        // Asignar el rol de administrador al usuario
        $adminUser->assignRole($adminRole->name);
    }
}
