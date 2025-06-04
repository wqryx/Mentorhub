<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CreateMentorshipSession extends Command
{
    protected $signature = 'mentorship:create-session 
                            {mentor_email : Email del mentor} 
                            {student_email : Email del estudiante}';
    
    protected $description = 'Crea una sesión de mentoría entre un mentor y un estudiante';

    public function handle()
    {
        // Obtener los usuarios
        $mentor = User::where('email', $this->argument('mentor_email'))->first();
        $student = User::where('email', $this->argument('student_email'))->first();

        // Verificar que existen
        if (!$mentor) {
            $this->error('No se encontró el mentor con el email: ' . $this->argument('mentor_email'));
            return 1;
        }

        if (!$student) {
            $this->error('No se encontró el estudiante con el email: ' . $this->argument('student_email'));
            return 1;
        }

        // Crear la sesión de mentoría
        $session = new \App\Models\MentorshipSession([
            'title' => 'Sesión de mentoría inicial',
            'description' => 'Sesión de mentoría entre ' . $mentor->name . ' y ' . $student->name,
            'mentor_id' => $mentor->id,
            'student_id' => $student->id,
            'type' => 'individual',
            'format' => 'video',
            'status' => 'scheduled',
            'start_time' => Carbon::now()->addDay(),
            'end_time' => Carbon::now()->addDay()->addHour(),
            'duration_minutes' => 60,
            'meeting_link' => 'https://meet.mentorhub.com/' . uniqid(),
            'student_goals' => 'Conocer al mentor y establecer objetivos de aprendizaje',
            'is_recurring' => false,
        ]);

        $session->save();

        $this->info('¡Sesión de mentoría creada exitosamente!');
        $this->info('ID de la sesión: ' . $session->id);
        $this->info('Mentor: ' . $mentor->name);
        $this->info('Estudiante: ' . $student->name);
        $this->info('Fecha: ' . $session->start_time->format('Y-m-d H:i'));
        $this->info('Enlace: ' . $session->meeting_link);

        return 0;
    }
}
