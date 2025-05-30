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
            $table->unsignedTinyInteger('progress')->default(0)->after('status');
            $table->timestamp('last_activity_at')->nullable()->after('progress');
            $table->unsignedBigInteger('current_tutorial_id')->nullable()->after('last_activity_at');
            
            // Agregar clave foránea para current_tutorial_id
            $table->foreign('current_tutorial_id')
                  ->references('id')
                  ->on('tutorials')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Eliminar la clave foránea primero
            $table->dropForeign(['current_tutorial_id']);
            
            // Eliminar las columnas agregadas
            $table->dropColumn(['progress', 'last_activity_at', 'current_tutorial_id']);
        });
    }
};
