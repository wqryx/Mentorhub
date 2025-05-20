<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('academic_year');
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->timestamps();
        });

        // Agregar índices útiles
        Schema::table('enrollments', function (Blueprint $table) {
            $table->unique(['user_id', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
}
