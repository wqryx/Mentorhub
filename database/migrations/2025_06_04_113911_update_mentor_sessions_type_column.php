<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero, modificar cualquier dato existente para asegurar compatibilidad
        DB::statement("UPDATE mentor_sessions SET type = 'one_time' WHERE type = 'individual'");
        DB::statement("UPDATE mentor_sessions SET type = 'recurring' WHERE type = 'group'");
        
        // Ahora modificar la definición de la columna
        // Primero eliminar la restricción ENUM anterior
        DB::statement("ALTER TABLE mentor_sessions MODIFY COLUMN type VARCHAR(20) NOT NULL DEFAULT 'one_time'");
        
        // Luego crear la nueva restricción ENUM
        DB::statement("ALTER TABLE mentor_sessions MODIFY COLUMN type ENUM('one_time', 'recurring') NOT NULL DEFAULT 'one_time'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a la definición original
        DB::statement("ALTER TABLE mentor_sessions MODIFY COLUMN type ENUM('individual', 'group') NOT NULL DEFAULT 'individual'");
        
        // Convertir datos de nuevo al formato anterior
        DB::statement("UPDATE mentor_sessions SET type = 'individual' WHERE type = 'one_time'");
        DB::statement("UPDATE mentor_sessions SET type = 'group' WHERE type = 'recurring'");
    }
};
