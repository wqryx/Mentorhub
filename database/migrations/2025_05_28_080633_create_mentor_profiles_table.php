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
        Schema::create('mentor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable(); // Biografía extendida del mentor
            $table->text('expertise')->nullable(); // Áreas de especialidad
            $table->string('years_experience')->nullable();
            $table->string('education')->nullable(); // Formación académica
            $table->string('certifications')->nullable(); // Certificaciones relevantes
            $table->boolean('is_verified')->default(false); // Si el perfil ha sido verificado por admins
            $table->boolean('is_available')->default(true); // Si está disponible para nuevas sesiones
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Tarifa por hora (si aplica)
            $table->decimal('rating', 3, 2)->nullable(); // Puntuación promedio de 0 a 5
            $table->integer('reviews_count')->default(0); // Número de reseñas recibidas
            $table->integer('sessions_count')->default(0); // Número de sesiones realizadas
            $table->string('time_zone')->nullable(); // Zona horaria para sesiones
            $table->text('mentoring_preferences')->nullable(); // Preferencias para mentorias
            $table->boolean('accepts_group_sessions')->default(true); // Si acepta sesiones grupales
            $table->integer('max_group_size')->default(5); // Tamaño máximo de grupo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_profiles');
    }
};
