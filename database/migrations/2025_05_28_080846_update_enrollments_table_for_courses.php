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
                // Nombres por defecto de las claves foráneas de Laravel y el índice único
                $userFkName = 'enrollments_user_id_foreign';
                $courseFkName = 'enrollments_course_id_foreign';
                $uniqueIndexName = 'enrollments_user_id_course_id_unique';

                // 1. Intentar eliminar las claves foráneas
                // Es importante eliminar las claves foráneas ANTES de intentar eliminar el índice único que podrían estar utilizando.
                try {
                    // Drop foreign key by column name for robustness
                    $table->dropForeign(['user_id']);
                    Log::info("Dropped foreign key on 'user_id' in 'enrollments' table.");
                } catch (\Exception $e) {
                    Log::info("Could not drop foreign key on 'user_id' in 'enrollments' table (it might not exist or another issue occurred): " . $e->getMessage());
                }
                try {
                    // Drop foreign key by column name for robustness
                    $table->dropForeign(['course_id']);
                    Log::info("Dropped foreign key on 'course_id' in 'enrollments' table.");
                } catch (\Exception $e) {
                    Log::info("Could not drop foreign key on 'course_id' in 'enrollments' table (it might not exist or another issue occurred): " . $e->getMessage());
                }

                // 2. Intentar eliminar el índice único
                try {
                    $table->dropUnique($uniqueIndexName);
                    Log::info("Dropped unique index '$uniqueIndexName' on 'enrollments' table.");
                } catch (\Illuminate\Database\QueryException $e) {
                    // Código de error 1091 para MySQL significa "Can't DROP INDEX ...; check that it exists"
                    if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1091) {
                        Log::info("Unique index '$uniqueIndexName' on 'enrollments' table did not exist or could not be dropped: " . $e->getMessage());
                    } else {
                        // Para otros errores, registrar una advertencia pero no necesariamente relanzar,
                        // para permitir que el script intente recrear el índice.
                        Log::warning("Failed to drop unique index '$uniqueIndexName' on 'enrollments' table: " . $e->getMessage());
                    }
                }

                // 3. Crear el índice único
                try {
                    $table->unique(['user_id', 'course_id'], $uniqueIndexName);
                    Log::info("Successfully created unique index '$uniqueIndexName' on 'enrollments' table.");
                } catch (\Illuminate\Database\QueryException $e) {
                    // Código de error 1061 para MySQL significa "Duplicate key name"
                    if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1061) {
                        Log::warning("Attempted to create unique index '$uniqueIndexName' on 'enrollments' table but it seems to exist or there was an issue: " . $e->getMessage());
                    } else {
                        Log::error("Failed to create unique index '$uniqueIndexName' on 'enrollments' table: " . $e->getMessage());
                        throw $e; // Relanzar si es un error inesperado y crítico
                    }
                }

                // 4. Re-agregar las claves foráneas
                // Esto es crucial para mantener la integridad referencial.
                // Asegurarse de que las columnas (user_id, course_id) y las tablas referenciadas (users, courses) existan.
                try {
                    // Solo añadir si no existe ya (podría no haber sido eliminada si el dropForeign falló por no existencia)
                    // Para verificar, usamos el Schema Manager de Doctrine que Laravel usa internamente.
                    $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
                    $foreignKeys = $schemaManager->listTableForeignKeys('enrollments');
                    $userFkExists = false;
                    foreach ($foreignKeys as $fk) {
                        if ($fk->getName() === $userFkName || (count($fk->getLocalColumns()) == 1 && $fk->getLocalColumns()[0] == 'user_id')) {
                            $userFkExists = true;
                            break;
                        }
                    }
                    if (!$userFkExists) {
                        $table->foreign('user_id', $userFkName)->references('id')->on('users')->onDelete('cascade');
                        Log::info("Re-added foreign key '$userFkName' on 'enrollments' table.");
                    } else {
                        Log::info("Foreign key for user_id ('$userFkName' or similar) already exists or was not dropped, skipping re-add.");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to re-add foreign key '$userFkName' on 'enrollments' table: " . $e->getMessage());
                }
                try {
                    $schemaManager = Schema::getConnection()->getDoctrineSchemaManager(); // Re-obtener por si acaso
                    $foreignKeys = $schemaManager->listTableForeignKeys('enrollments');
                    $courseFkExists = false;
                    foreach ($foreignKeys as $fk) {
                        if ($fk->getName() === $courseFkName || (count($fk->getLocalColumns()) == 1 && $fk->getLocalColumns()[0] == 'course_id')) {
                            $courseFkExists = true;
                            break;
                        }
                    }
                    if (!$courseFkExists) {
                        $table->foreign('course_id', $courseFkName)->references('id')->on('courses')->onDelete('cascade');
                        Log::info("Re-added foreign key '$courseFkName' on 'enrollments' table.");
                    } else {
                        Log::info("Foreign key for course_id ('$courseFkName' or similar) already exists or was not dropped, skipping re-add.");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to re-add foreign key '$courseFkName' on 'enrollments' table: " . $e->getMessage());
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
