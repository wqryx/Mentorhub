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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('meeting_url')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('status', ['draft', 'active', 'cancelled', 'completed'])->default('draft');
            $table->enum('type', ['mentorship', 'workshop', 'webinar', 'other'])->default('other');
            $table->integer('capacity')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla pivote para la relación muchos a muchos entre eventos y usuarios
        Schema::create('event_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['registered', 'confirmed', 'cancelled', 'waitlisted'])->default('registered');
            $table->boolean('attended')->default(false);
            $table->timestamps();
            
            // Asegurarse de que un usuario no pueda registrarse más de una vez al mismo evento
            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_user');
        Schema::dropIfExists('events');
    }
};
