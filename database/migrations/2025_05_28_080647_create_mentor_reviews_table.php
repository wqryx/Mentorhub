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
        Schema::create('mentor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade'); // Mentor evaluado
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // Estudiante que evalúa
            $table->foreignId('session_id')->constrained('mentor_sessions')->onDelete('cascade'); // Sesión evaluada
            $table->integer('rating'); // Puntuación de 1 a 5
            $table->text('comment')->nullable(); // Comentario sobre la sesión
            $table->json('rating_aspects')->nullable(); // Detalles de calificación por aspectos (conocimiento, comunicación, etc.)
            $table->boolean('is_anonymous')->default(false); // Si la reseña es anónima
            $table->boolean('is_published')->default(true); // Si la reseña se muestra públicamente
            $table->boolean('is_verified')->default(false); // Si ha sido verificada por un administrador
            $table->boolean('is_flagged')->default(false); // Si ha sido reportada
            $table->string('flagged_reason')->nullable(); // Razón del reporte
            $table->text('mentor_response')->nullable(); // Respuesta del mentor a la reseña
            $table->timestamp('mentor_responded_at')->nullable(); // Cuándo respondió el mentor
            $table->timestamps();
            $table->softDeletes();
            
            // Un estudiante solo puede dejar una reseña por sesión
            $table->unique(['student_id', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_reviews');
    }
};
