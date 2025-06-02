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
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Icono para el foro
            $table->unsignedBigInteger('parent_id')->nullable(); // Para subcategorías
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade'); // Para foros específicos de un curso
            $table->boolean('is_active')->default(true);
            $table->boolean('is_private')->default(false); // Si solo ciertos roles pueden ver
            $table->integer('order')->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Añadir la clave foránea auto-referencial después de crear la tabla
        Schema::table('forums', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('forums')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forums');
    }
};
