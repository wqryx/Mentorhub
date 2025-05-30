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
        Schema::create('course_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->decimal('final_grade', 5, 2)->nullable(); // Calificación final si aplica
            $table->string('status')->default('in_progress'); // in_progress, completed, abandoned, failed
            $table->string('certificate_number')->nullable(); // Número de certificado
            $table->timestamp('certificate_issued_at')->nullable(); // Fecha de emisión del certificado
            $table->text('notes')->nullable(); // Notas personales del estudiante
            $table->json('module_progress')->nullable(); // Progreso detallado por módulo
            $table->boolean('is_featured')->default(false); // Si el estudiante lo destaca en su perfil
            $table->timestamps();
            $table->softDeletes();
            
            // Un usuario solo puede tener un registro de historial por curso
            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_histories');
    }
};
