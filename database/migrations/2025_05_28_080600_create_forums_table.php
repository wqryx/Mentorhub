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
            $table->foreignId('parent_id')->nullable()->constrained('forums')->onDelete('cascade'); // Para subcategorías
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade'); // Para foros específicos de un curso
            $table->boolean('is_active')->default(true);
            $table->boolean('is_private')->default(false); // Si solo ciertos roles pueden ver
            $table->integer('order')->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
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
