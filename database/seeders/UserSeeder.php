<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1 // ID del rol admin (asumiendo que en RoleSeeder se insertó primero)
        ]);
        
        User::create([
            'name' => 'mentor',
            'email' => 'mentor@gmail.com',
            'password' => Hash::make('mentor123'),
            'role_id' => 2
        ]);
        
        User::create([
            'name' => 'estudiante',
            'email' => 'estudiante@gmail.com',
            'password' => Hash::make('estudiante123'),
            'role_id' => 3
        ]);
        
    }
}
