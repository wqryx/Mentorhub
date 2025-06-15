<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\MentorshipSession;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CheckMentorStudentRelations extends Command
{
    protected $signature = 'mentor:check-relations {mentor_id} {student_id}';
    protected $description = 'Verifica las relaciones entre un mentor y un estudiante';

    public function handle()
    {
        $mentorId = $this->argument('mentor_id');
        $studentId = $this->argument('student_id');

        // Verificar si los usuarios existen
        $mentor = User::find($mentorId);
        $student = User::find($studentId);

        if (!$mentor || !$student) {
            $this->error('Uno o ambos usuarios no existen');
            if (!$mentor) $this->error("No se encontró el mentor con ID: {$mentorId}");
            if (!$student) $this->error("No se encontró el estudiante con ID: {$studentId}");
            return 1;
        }

        $this->info("Mentor: {$mentor->name} (ID: {$mentor->id})");
        $this->info("Estudiante: {$student->name} (ID: {$student->id})\n");

        // 1. Verificar sesiones de mentoría
        $sessions = MentorshipSession::where('mentor_id', $mentorId)
            ->where('student_id', $studentId)
            ->get();

        $this->info("Sesiones de mentoría entre ambos:");
        if ($sessions->isEmpty()) {
            $this->line("  - No hay sesiones registradas");
        } else {
            foreach ($sessions as $session) {
                $this->line("  - ID: {$session->id}, Título: {$session->title}, Estado: {$session->status}, Fecha: {$session->start_time}");
            }
        }

        // 2. Verificar cursos del mentor
        $courses = Course::where('creator_id', $mentorId)->get();
        $this->info("\nCursos creados por el mentor: " . $courses->count());

        // 3. Verificar si el estudiante está inscrito en algún curso del mentor
        if (!$courses->isEmpty()) {
            $enrolled = DB::table('enrollments')
                ->whereIn('course_id', $courses->pluck('id'))
                ->where('user_id', $studentId)
                ->exists();

            $this->info("El estudiante " . ($enrolled ? 'SÍ' : 'NO') . " está inscrito en al menos un curso del mentor");
        }

        // 4. Verificar relaciones directas en la tabla de mentor_estudiante si existe
        if (\Schema::hasTable('mentor_student')) {
            $directRelation = DB::table('mentor_student')
                ->where('mentor_id', $mentorId)
                ->where('student_id', $studentId)
                ->exists();

            $this->info("\nRelación directa en tabla mentor_student: " . ($directRelation ? 'SÍ' : 'NO'));
        }

        return 0;
    }
}
