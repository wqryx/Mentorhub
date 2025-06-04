<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Agregar referencia al curso
            if (!Schema::hasColumn('enrollments', 'course_id')) {
                $table->foreignId('course_id')->nullable()->constrained()->after('user_id');
            }
            
            // Modificar el enum de estado para adaptarlo a cursos
            if (Schema::hasColumn('enrollments', 'status')) {
                $table->dropColumn('status');
            }
            $table->enum('status', ['pending', 'active', 'completed', 'expired', 'cancelled'])
                  ->default('pending')
                  ->after('course_id');
            
            // Eliminar el campo academic_year que ya no se necesita
            if (Schema::hasColumn('enrollments', 'academic_year')) {
                $table->dropColumn('academic_year');
            }
            
            // Agregar campos adicionales para cursos
            if (!Schema::hasColumn('enrollments', 'enrolled_at')) {
                $table->timestamp('enrolled_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('enrollments', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('enrolled_at');
            }
            if (!Schema::hasColumn('enrollments', 'progress_percentage')) {
                $table->integer('progress_percentage')->default(0)->after('completed_at');
            }
            if (!Schema::hasColumn('enrollments', 'certificate_number')) {
                $table->string('certificate_number')->nullable()->after('progress_percentage');
            }
            if (!Schema::hasColumn('enrollments', 'certificate_issued_at')) {
                $table->timestamp('certificate_issued_at')->nullable()->after('certificate_number');
            }
            
            // Eliminar el índice único existente (user_id, academic_year)
            if (Schema::hasColumn('enrollments', 'user_id') && Schema::hasColumn('enrollments', 'academic_year')) {
                try {
                    // El nombre del índice por defecto en Laravel para unique(['user_id', 'academic_year']) es 'enrollments_user_id_academic_year_unique'
                    $table->dropUnique('enrollments_user_id_academic_year_unique');
                } catch (\Illuminate\Database\QueryException $e) {
                    if ($e->errorInfo[1] == 1091) { // MySQL error code for "Can't DROP ...; check that it exists"
                        Log::info("Index 'enrollments_user_id_academic_year_unique' does not exist on 'enrollments' table, skipping drop.");
                    } else {
                        throw $e; // Re-throw otras QueryExceptions
                    }
                }
            }
            
            // Asegurar que el índice único para user_id y course_id exista correctamente
            if (Schema::hasColumn('enrollments', 'user_id') && Schema::hasColumn('enrollments', 'course_id')) {
                // Primero, intentar eliminar el índice si existe, para evitar el error de duplicado al crearlo
                try {
                    $table->dropUnique('enrollments_user_id_course_id_unique');
                    Log::info("Dropped existing index 'enrollments_user_id_course_id_unique' before re-creating.");
                } catch (\Illuminate\Database\QueryException $e) {
                    // MySQL error code 1091: Can't DROP INDEX; check that it exists
                    if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1091) {
                        Log::info("Index 'enrollments_user_id_course_id_unique' did not exist, no need to drop.");
                    } else {
                        // Si es otro error, relanzar
                        throw $e;
                    }
                }

                // Ahora, crear el índice
                try {
                    $table->unique(['user_id', 'course_id'], 'enrollments_user_id_course_id_unique');
                    Log::info("Successfully created index 'enrollments_user_id_course_id_unique'.");
                } catch (\Illuminate\Database\QueryException $e) {
                    // MySQL error code 1061: Duplicate key name
                    // Esto no debería ocurrir si el dropUnique anterior funcionó, pero lo mantenemos por si acaso
                    if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1061) {
                        Log::warning("Attempted to create index 'enrollments_user_id_course_id_unique' but it still seems to exist despite drop attempt.");
                    } else {
                        throw $e;
                    }
                }
            }
            
            // Agregar soft deletes
            if (!Schema::hasColumn('enrollments', 'deleted_at')) {
                $table->softDeletes();
            }
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
