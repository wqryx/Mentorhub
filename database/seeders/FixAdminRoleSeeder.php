<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class FixAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el usuario administrador
        $admin = User::where('email', 'admin@mentorhub.com')->first();
        
        if ($admin) {
            // Obtener el rol de administrador
            $adminRole = Role::where('name', 'Admin')->first();
            
            if ($adminRole) {
                // Asignar el rol al usuario
                $admin->roles()->sync([$adminRole->id]);
                echo "Rol de administrador asignado correctamente al usuario.\n";
            } else {
                echo "No se encontró el rol de administrador.\n";
            }
        } else {
            echo "No se encontró el usuario administrador.\n";
        }
    }
}
