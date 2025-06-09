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
        if (!Schema::hasTable('course_histories')) {
            Schema::create('course_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('tutorial_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('content_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['in_progress', 'completed', 'not_started'])->default('not_started');
            $table->integer('progress')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Asegurar que un usuario solo tenga un registro por contenido
            $table->unique(['user_id', 'course_id', 'module_id', 'tutorial_id', 'content_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_histories');
    }
};
