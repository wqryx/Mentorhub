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
        Schema::table('enrollments', function (Blueprint $table) {
            // Agregar referencia al curso
            $table->foreignId('course_id')->nullable()->constrained()->after('user_id');
            
            // Modificar el enum de estado para adaptarlo a cursos
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'active', 'completed', 'expired', 'cancelled'])
                  ->default('pending')
                  ->after('course_id');
            
            // Eliminar el campo academic_year que ya no se necesita
            $table->dropColumn('academic_year');
            
            // Agregar campos adicionales para cursos
            $table->timestamp('enrolled_at')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->after('enrolled_at');
            $table->integer('progress_percentage')->default(0)->after('completed_at');
            $table->string('certificate_number')->nullable()->after('progress_percentage');
            $table->timestamp('certificate_issued_at')->nullable()->after('certificate_number');
            
            // Eliminar el índice único existente
            $table->dropUnique(['user_id', 'academic_year']);
            
            // Agregar nuevo índice único para user_id y course_id
            $table->unique(['user_id', 'course_id']);
            
            // Agregar soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
