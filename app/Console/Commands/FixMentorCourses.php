<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\User;
use Illuminate\Console\Command;

class FixMentorCourses extends Command
{
    protected $signature = 'mentor:fix-courses {mentor_id}';
    protected $description = 'Asigna los cursos existentes al mentor especificado';

    public function handle()
    {
        $mentorId = $this->argument('mentor_id');
        $mentor = User::find($mentorId);

        if (!$mentor) {
            $this->error("No se encontró el mentor con ID: {$mentorId}");
            return 1;
        }

        if (!$mentor->isMentor()) {
            $this->error("El usuario con ID {$mentorId} no tiene el rol de mentor");
            return 1;
        }

        // Actualizar los cursos existentes para que sean del mentor
        $updated = Course::whereNull('creator_id')
            ->orWhere('creator_id', '!=', $mentorId)
            ->update(['creator_id' => $mentorId]);

        $this->info("Se actualizaron {$updated} cursos para que sean del mentor: {$mentor->name} (ID: {$mentor->id})");

        // Verificar los cursos del mentor
        $mentorCourses = $mentor->courses()->count();
        $this->info("El mentor ahora tiene {$mentorCourses} cursos asignados");

        return 0;
    }
}
