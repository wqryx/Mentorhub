<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:prune {--days=90 : Número de días a mantener} {--force : Forzar sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina registros de actividad antiguos para mantener la base de datos optimizada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $force = $this->option('force');
        
        $date = Carbon::now()->subDays($days);
        
        $count = ActivityLog::where('created_at', '<', $date)->count();
        
        if ($count === 0) {
            $this->info('No hay registros de actividad más antiguos que ' . $days . ' días.');
            return;
        }
        
        $this->info('Se eliminarán ' . $count . ' registros de actividad más antiguos que ' . $days . ' días.');
        
        if (!$force && !$this->confirm('¿Desea continuar?')) {
            $this->info('Operación cancelada.');
            return;
        }
        
        $deleted = ActivityLog::where('created_at', '<', $date)->delete();
        
        $this->info('Se han eliminado ' . $deleted . ' registros de actividad.');
    }
}
