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
        // Primero crear la tabla sin las claves foráneas
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->enum('status', ['pending', 'active', 'completed', 'expired', 'cancelled'])->default('pending');
            $table->date('enrollment_date');
            $table->date('completion_date')->nullable();
            $table->timestamps();
            
            // Asegurarse de que un usuario no se inscriba dos veces al mismo curso
            $table->unique(['user_id', 'course_id']);
        });
        
        // Luego, en una transacción separada, agregar las claves foráneas
        // para asegurar que las tablas referenciadas ya existan
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
