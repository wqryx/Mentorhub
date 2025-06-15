<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Course;
use App\Models\MentorshipSession;
use Illuminate\Console\Command;

class CheckRelations extends Command
{
    protected $signature = 'check:relations {mentor_id} {student_id}';
    protected $description = 'Verifica las relaciones entre un mentor y un estudiante';

    public function handle()
    {
        $mentor = User::with(['courses', 'mentorSessions', 'students'])->find($this->argument('mentor_id'));
        $student = User::with(['enrolledCourses', 'menteeSessions', 'mentors'])->find($this->argument('student_id'));

        if (!$mentor || !$student) {
            $this->error('Uno o ambos usuarios no existen');
            return 1;
        }

        $this->info("=== VERIFICACIÓN DE RELACIONES ===\n");

        // 1. Información del mentor
        $this->info("MENTOR: {$mentor->name} (ID: {$mentor->id})");
        $this->line("Roles: " . $mentor->getRoleNames()->implode(', '));
        $this->line("Cursos creados: " . $mentor->courses->count());
        $this->line("Sesiones como mentor: " . $mentor->mentorSessions->count());
        $this->line("Estudiantes: " . $mentor->students->count() . "\n");

        // 2. Cursos del mentor
        if ($mentor->courses->isNotEmpty()) {
            $this->info("CURSOS DEL MENTOR:");
            foreach ($mentor->courses as $course) {
                $this->line("- {$course->title} (ID: {$course->id})");
                $this->line("  Descripción: " . ($course->description ?: 'Sin descripción'));
                $this->line("  Estudiantes inscritos: " . $course->students()->count());
            }
            $this->line('');
        }

        // 3. Información del estudiante
        $this->info("ESTUDIANTE: {$student->name} (ID: {$student->id})");
        $this->line("Roles: " . $student->getRoleNames()->implode(', '));
        $this->line("Cursos inscritos: " . $student->enrolledCourses->count());
        $this->line("Sesiones como estudiante: " . $student->menteeSessions->count());
        $this->line("Mentores: " . $student->mentors->count() . "\n");

        // 4. Sesiones entre mentor y estudiante
        $sessions = MentorshipSession::where('mentor_id', $mentor->id)
            ->where('student_id', $student->id)
            ->with(['course'])
            ->get();

        if ($sessions->isNotEmpty()) {
            $this->info("SESIONES ENTRE MENTOR Y ESTUDIANTE:");
            foreach ($sessions as $session) {
                $this->line("- {$session->title} (ID: {$session->id})");
                $this->line("  Curso: " . ($session->course ? $session->course->title : 'Sin curso'));
                $this->line("  Fecha: {$session->start_time->format('Y-m-d H:i')}");
                $this->line("  Estado: {$session->status}");
            }
        } else {
            $this->info("No hay sesiones registradas entre este mentor y estudiante.");
        }

        return 0;
    }
}
