<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Mentor;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $adminRole = Role::where('name', 'Admin')->first();
        $mentorRole = Role::where('name', 'Mentor')->first();
        $studentRole = Role::where('name', 'Estudiante')->first();
        $guestRole = Role::where('name', 'Guest')->first();

        // Administrador
        $admin = User::create([
            'name' => 'Juan Pérez',
            'email' => 'admin@mentorhub.com',
            'password' => Hash::make('admin123'),
        ]);
        $admin->roles()->attach($adminRole);

        // Mentores
        $mentor1 = User::create([
            'name' => 'María García',
            'email' => 'mentor1@mentorhub.com',
            'password' => Hash::make('mentor123'),
        ]);
        $mentor1->roles()->attach($mentorRole);

        $mentor2 = User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'mentor2@mentorhub.com',
            'password' => Hash::make('mentor123'),
        ]);
        $mentor2->roles()->attach($mentorRole);

        // Estudiantes
        $student1 = User::create([
            'name' => 'Ana Martínez',
            'email' => 'student1@mentorhub.com',
            'password' => Hash::make('student123'),
        ]);
        $student1->roles()->attach($studentRole);

        $student2 = User::create([
            'name' => 'Pedro Sánchez',
            'email' => 'student2@mentorhub.com',
            'password' => Hash::make('student123'),
        ]);
        $student2->roles()->attach($studentRole);

        // Guest
        $guest = User::create([
            'name' => 'Invitado',
            'email' => 'guest@mentorhub.com',
            'password' => Hash::make('guest123'),
        ]);
        $guest->roles()->attach($guestRole);
    }
}
