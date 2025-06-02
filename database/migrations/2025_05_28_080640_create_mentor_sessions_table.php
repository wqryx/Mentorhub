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
        Schema::create('mentor_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade'); // Mentor asignado
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // Estudiante solicitante
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null'); // Curso relacionado (si aplica)
            $table->enum('type', ['individual', 'group'])->default('individual');
            $table->enum('format', ['video', 'audio', 'chat', 'in-person'])->default('video');
            $table->enum('status', ['requested', 'scheduled', 'confirmed', 'completed', 'cancelled', 'no-show'])->default('requested');
            $table->timestamp('requested_at')->useCurrent();
            $table->dateTime('start_time')->nullable(); // Hora de inicio programada
            $table->dateTime('end_time')->nullable(); // Hora de finalización programada
            $table->integer('duration_minutes')->default(60); // Duración en minutos
            $table->string('meeting_link')->nullable(); // Enlace a la sesión (Zoom, Meet, etc.)
            $table->text('student_goals')->nullable(); // Objetivos del estudiante para la sesión
            $table->text('mentor_notes')->nullable(); // Notas privadas del mentor
            $table->text('outcome_summary')->nullable(); // Resumen de resultados (después de la sesión)
            $table->boolean('is_recurring')->default(false); // Si es una sesión recurrente
            $table->string('recurrence_pattern')->nullable(); // Patrón de recurrencia (weekly, biweekly, etc.)
            $table->decimal('session_fee', 8, 2)->nullable(); // Tarifa de la sesión (si aplica)
            $table->boolean('is_paid')->default(false); // Si la sesión ha sido pagada (si aplica)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_sessions');
    }
};
