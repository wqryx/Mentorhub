<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\MentorshipSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AssignStudentToMentor extends Command
{
    protected $signature = 'mentor:assign-student';
    protected $description = 'Asigna un estudiante de ejemplo a un mentor principal';

    public function handle()
    {
        // 1. Crear o obtener el estudiante
        $student = User::firstOrCreate(
            ['email' => 'estudiante@ejemplo.com'],
            [
                'name' => 'Estudiante Ejemplo',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );

        $this->info('Estudiante: ' . $student->name . ' (ID: ' . $student->id . ')');

        // 2. Crear o obtener el mentor
        $mentor = User::firstOrCreate(
            ['email' => 'mentor@ejemplo.com'],
            [
                'name' => 'Mentor Principal',
                'password' => Hash::make('password123'),
                'role' => 'mentor',
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );

        $this->info('Mentor: ' . $mentor->name . ' (ID: ' . $mentor->id . ')');

        // 3. Asignar roles si se usa Spatie
        if (class_exists('Spatie\Permission\Models\Role')) {
            if (!$student->hasRole('student')) {
                $student->assignRole('student');
                $this->info('Rol de estudiante asignado');
            }
            if (!$mentor->hasRole('mentor')) {
                $mentor->assignRole('mentor');
                $this->info('Rol de mentor asignado');
            }
        }

        // 4. Crear la sesión de mentoría
        $session = MentorshipSession::firstOrCreate(
            [
                'mentor_id' => $mentor->id,
                'student_id' => $student->id
            ],
            [
                'status' => 'active',
                'scheduled_time' => now()->addDay(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $this->info('Sesión de mentoría creada con ID: ' . $session->id);
        $this->info('\n¡Proceso completado con éxito!');
        $this->info('Puedes iniciar sesión con:');
        $this->info('- Mentor: mentor@ejemplo.com / password123');
        $this->info('- Estudiante: estudiante@ejemplo.com / password123');
    }
}
