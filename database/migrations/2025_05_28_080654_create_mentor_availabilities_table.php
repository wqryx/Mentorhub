<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mentor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time'); // Hora de inicio de disponibilidad
            $table->time('end_time'); // Hora de fin de disponibilidad
            $table->boolean('is_recurring')->default(true); // Si es un horario recurrente cada semana
            $table->date('specific_date')->nullable(); // Para disponibilidad en una fecha específica
            $table->string('time_zone')->default('UTC'); // Zona horaria
            $table->boolean('is_available')->default(true); // Si el horario está activo o inactivo
            $table->text('notes')->nullable(); // Notas adicionales sobre esta disponibilidad
            $table->timestamps();

            // Índice para búsquedas rápidas
            $table->index(['mentor_id', 'day_of_week', 'is_available']);
            $table->index(['mentor_id', 'specific_date', 'is_available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_availabilities');
    }
};
