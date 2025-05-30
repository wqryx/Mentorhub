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
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('forum_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Creador del hilo
            $table->boolean('is_sticky')->default(false); // Hilos anclados/destacados
            $table->boolean('is_locked')->default(false); // Hilos cerrados (no se permiten más respuestas)
            $table->boolean('is_resolved')->default(false); // Si el hilo ha sido resuelto
            $table->foreignId('resolved_by')->nullable()->constrained('users'); // Usuario que marcó como resuelto
            $table->integer('views_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->foreignId('last_post_by')->nullable()->constrained('users'); // Último usuario en comentar
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};
