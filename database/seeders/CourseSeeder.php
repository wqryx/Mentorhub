<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asignar los cursos al mentor con ID 2
        $teacher = User::find(2);

        // Si el mentor con ID 2 no existe, se detiene el seeder o se asigna a un admin por defecto.
        if (!$teacher) {
            $this->command->error('Mentor with ID 2 not found. Courses will not be seeded or assign to a default user if available.');
            // Opcional: buscar un admin o el primer usuario como fallback
            // $teacher = User::where('role', 'admin')->first() ?? User::first();
            // if (!$teacher) return;
            return; // Detener si no se encuentra el mentor específico
        }
        
        // Crear cursos de ejemplo
        $coursesData = [
            [
                'name' => 'Desarrollo Web con Laravel 11',
                'description' => 'Aprende a construir aplicaciones web robustas y escalables con la última versión de Laravel.',
                'code' => 'LARAVEL11',
                'credits' => 5,
                'hours_per_week' => 8,
                'level' => 'Intermedio-Avanzado',
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(4),
                'classroom' => 'Online',
                'schedule' => 'Lunes, Miércoles y Viernes 19:00-21:00',
                'teacher_id' => $teacher->id // Asegúrate que tu tabla courses usa user_id para el profesor
            ],
            [
                'name' => 'Programación PHP Avanzada y Patrones de Diseño',
                'description' => 'Profundiza en PHP, aprende patrones de diseño y buenas prácticas para escribir código de alta calidad.',
                'code' => 'PHPADV',
                'credits' => 4,
                'hours_per_week' => 6,
                'level' => 'Avanzado',
                'is_active' => true,
                'start_date' => now()->addDays(10),
                'end_date' => now()->addMonths(3),
                'classroom' => 'Online',
                'schedule' => 'Martes y Jueves 18:00-21:00',
                'teacher_id' => $teacher->id
            ],
            [
                'name' => 'Creación de APIs RESTful con Lumen y Laravel',
                'description' => 'Desarrolla APIs rápidas y eficientes utilizando Lumen y Laravel, ideales para aplicaciones móviles y microservicios.',
                'code' => 'WEB301',
                'credits' => 5,
                'hours_per_week' => 8,
                'level' => 'Avanzado',
                'is_active' => true,
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(5),
                'classroom' => 'Aula Virtual 3',
                'schedule' => 'Viernes 15:00-19:00 y Sábados 9:00-13:00',
                'teacher_id' => $teacher->id
            ],
        ];
        
        foreach ($coursesData as $courseDetails) {
            Course::updateOrCreate(['code' => $courseDetails['code']], $courseDetails);
        }

        $this->command->info('Cursos de programación (Laravel/PHP) creados y asignados al mentor ID 2.');
    }
}
