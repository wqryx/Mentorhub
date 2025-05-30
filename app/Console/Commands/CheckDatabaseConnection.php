<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckDatabaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar la conexión a la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Intentando conectar a la base de datos...');
            
            // Obtener la configuración de la base de datos
            $connection = config('database.default');
            $database = config("database.connections.{$connection}.database");
            $host = config("database.connections.{$connection}.host");
            $port = config("database.connections.{$connection}.port");
            $username = config("database.connections.{$connection}.username");
            
            $this->info("Configuración de conexión:");
            $this->line(" - Driver: {$connection}");
            $this->line(" - Host: {$host}");
            $this->line(" - Puerto: {$port}");
            $this->line(" - Base de datos: {$database}");
            $this->line(" - Usuario: {$username}");
            
            // Intentar conectar
            DB::connection()->getPdo();
            
            // Si llegamos aquí, la conexión fue exitosa
            $this->info('✅ Conexión exitosa a la base de datos!');
            
            // Intentar listar las tablas
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            $this->info("\nSe encontraron {$tableCount} tablas en la base de datos.");
            
            if ($tableCount > 0) {
                $this->info("\nLista de tablas:");
                foreach ($tables as $table) {
                    $tableArray = (array)$table;
                    $this->line(" - " . reset($tableArray));
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error al conectar a la base de datos:');
            $this->error($e->getMessage());
            
            // Mostrar sugerencias para solucionar problemas comunes
            $this->warn("\nSugerencias para solucionar el problema:");
            $this->line("1. Verifica que el servidor de base de datos esté en ejecución");
            $this->line("2. Verifica que las credenciales en el archivo .env sean correctas");
            $this->line("3. Asegúrate de que la base de datos exista y el usuario tenga permisos");
            $this->line("4. Verifica que el puerto de conexión sea el correcto");
            
            return 1;
        }
    }
}
