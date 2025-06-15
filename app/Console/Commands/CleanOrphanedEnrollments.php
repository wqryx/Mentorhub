<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;

class CleanOrphanedEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollments:clean-orphaned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove enrollments that reference non-existent courses';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = 0;
        
        // Obtener todas las matrículas que no tienen un curso existente
        $enrollments = Enrollment::whereDoesntHave('course')->get();
        
        if ($enrollments->isEmpty()) {
            $this->info('No se encontraron matrículas huérfanas.');
            return 0;
        }
        
        // Mostrar resumen de las matrículas a eliminar
        $this->warn("Se encontraron {$enrollments->count()} matrículas huérfanas.");
        
        if ($this->confirm('¿Desea ver los detalles de las matrículas que se eliminarán?', false)) {
            $this->table(
                ['ID', 'Usuario ID', 'Curso ID', 'Estado', 'Creado'],
                $enrollments->map(function ($enrollment) {
                    return [
                        'id' => $enrollment->id,
                        'user_id' => $enrollment->user_id,
                        'course_id' => $enrollment->course_id,
                        'status' => $enrollment->status,
                        'created_at' => $enrollment->created_at->format('Y-m-d H:i:s'),
                    ];
                })
            );
        }
        
        // Confirmar antes de eliminar
        if ($this->confirm('¿Está seguro de que desea eliminar estas matrículas?', false)) {
            $count = $enrollments->count();
            $enrollments->each->delete();
            $this->info("Se eliminaron {$count} matrículas huérfanas correctamente.");
        } else {
            $this->info('Operación cancelada. No se eliminó ninguna matrícula.');
        }
        
        return 0;
    }
}
