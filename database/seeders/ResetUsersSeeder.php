<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class ResetUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Desactivar las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Borrar todos los registros relacionados
        DB::table('activity_logs')->delete();
        DB::table('model_has_roles')->delete();
        User::truncate();

        // Reactivar las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Crear nuevo usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@mentorhub.com',
            'password' => Hash::make('admin123'),
        ]);

        // Asignar el rol de administrador
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $admin->assignRole($adminRole->name);
        }

        // Crear usuario mentor
        $mentor = User::create([
            'name' => 'Mentor Demo',
            'email' => 'mentor@mentorhub.com',
            'password' => Hash::make('mentor123'),
        ]);

        // Asignar el rol de mentor
        $mentorRole = Role::where('name', 'Mentor')->first();
        if ($mentorRole) {
            $mentor->assignRole($mentorRole->name);
        }

        // Crear usuario estudiante
        $student = User::create([
            'name' => 'Estudiante Demo',
            'email' => 'student@mentorhub.com',
            'password' => Hash::make('student123'),
        ]);

        // Asignar el rol de estudiante
        $studentRole = Role::where('name', 'Estudiante')->first();
        if ($studentRole) {
            $student->assignRole($studentRole->name);
        }

        echo "Usuarios reseteados y creados correctamente.\n";
    }
}
