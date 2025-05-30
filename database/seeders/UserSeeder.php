<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $mentorRole = Role::firstOrCreate(['name' => 'Mentor', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'Estudiante', 'guard_name' => 'web']);
        
        // Crear usuario Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@mentorhub.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@mentorhub.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles([$adminRole]);

        // Crear usuario Mentor
        $mentor = User::firstOrCreate(
            ['email' => 'mentor@mentorhub.com'],
            [
                'name' => 'Mentor Ejemplo',
                'email' => 'mentor@mentorhub.com',
                'password' => Hash::make('mentor123'),
                'email_verified_at' => now(),
            ]
        );
        $mentor->syncRoles([$mentorRole]);

        // Crear usuario Estudiante
        $student = User::firstOrCreate(
            ['email' => 'estudiante@mentorhub.com'],
            [
                'name' => 'Estudiante Ejemplo',
                'email' => 'estudiante@mentorhub.com',
                'password' => Hash::make('estudiante123'),
                'email_verified_at' => now(),
            ]
        );
        $student->syncRoles([$studentRole]);
        
        $this->command->info('Usuarios de prueba creados exitosamente!');
        $this->command->info('Admin: admin@mentorhub.com / admin123');
        $this->command->info('Mentor: mentor@mentorhub.com / mentor123');
        $this->command->info('Estudiante: estudiante@mentorhub.com / estudiante123');
    }
}
