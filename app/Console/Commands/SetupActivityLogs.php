<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SetupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura la tabla de registros de actividad manualmente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Configurando la tabla de registros de actividad...');
        
        if (Schema::hasTable('activity_logs')) {
            $this->info('La tabla activity_logs ya existe. Verificando estructura...');
            
            // Verificar si faltan columnas y agregarlas si es necesario
            if (!Schema::hasColumn('activity_logs', 'user_id')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                });
                $this->info('Agregada columna user_id');
            }
            
            if (!Schema::hasColumn('activity_logs', 'action')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->string('action');
                });
                $this->info('Agregada columna action');
            }
            
            if (!Schema::hasColumn('activity_logs', 'description')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->text('description');
                });
                $this->info('Agregada columna description');
            }
            
            if (!Schema::hasColumn('activity_logs', 'model_type')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->string('model_type')->nullable();
                });
                $this->info('Agregada columna model_type');
            }
            
            if (!Schema::hasColumn('activity_logs', 'model_id')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->unsignedBigInteger('model_id')->nullable();
                });
                $this->info('Agregada columna model_id');
            }
            
            if (!Schema::hasColumn('activity_logs', 'properties')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->json('properties')->nullable();
                });
                $this->info('Agregada columna properties');
            }
            
            if (!Schema::hasColumn('activity_logs', 'ip_address')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->string('ip_address')->nullable();
                });
                $this->info('Agregada columna ip_address');
            }
            
            if (!Schema::hasColumn('activity_logs', 'user_agent')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->text('user_agent')->nullable();
                });
                $this->info('Agregada columna user_agent');
            }
            
            $this->info('Verificación de estructura completada.');
        } else {
            $this->info('Creando tabla activity_logs...');
            
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('action');
                $table->text('description');
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('properties')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
                
                // Índices para mejorar el rendimiento de las consultas
                $table->index('action');
                $table->index('created_at');
                $table->index(['model_type', 'model_id']);
            });
            
            $this->info('Tabla activity_logs creada exitosamente.');
        }
        
        $this->info('Configuración completada.');
    }
}
