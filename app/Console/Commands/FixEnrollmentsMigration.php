<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixEnrollmentsMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:enrollments-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra manualmente la migración de la tabla enrollments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('enrollments')) {
            $this->error('La tabla enrollments no existe en la base de datos.');
            return 1;
        }

        $migration = '2025_05_28_000000_create_enrollments_table';
        
        // Verificar si la migración ya está registrada
        $exists = DB::table('migrations')
            ->where('migration', $migration)
            ->exists();

        if ($exists) {
            $this->info('La migración ya está registrada en la base de datos.');
            return 0;
        }

        // Obtener el batch más alto y sumar 1
        $batch = DB::table('migrations')->max('batch') + 1;

        // Insertar el registro de la migración
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $batch
        ]);

        $this->info('Migración registrada exitosamente en el batch ' . $batch);
        return 0;
    }
}
