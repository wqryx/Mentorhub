<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear los roles básicos del sistema
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Administrador del sistema con acceso completo'
            ],
            [
                'name' => 'Mentor',
                'description' => 'Mentor/Profesor que puede crear cursos y evaluar estudiantes'
            ],
            [
                'name' => 'Estudiante',
                'description' => 'Estudiante que puede inscribirse en cursos'
            ],
            [
                'name' => 'Guest',
                'description' => 'Usuario invitado con acceso limitado'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
