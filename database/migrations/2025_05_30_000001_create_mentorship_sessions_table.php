<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMentorshipSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mentorship_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('scheduled_at');
            $table->integer('duration'); // en minutos
            $table->string('meeting_url')->nullable();
            $table->enum('status', ['pending', 'scheduled', 'completed', 'cancelled', 'reschedule_requested'])->default('pending');
            $table->text('mentor_notes')->nullable();
            $table->text('mentee_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->dateTime('proposed_time')->nullable(); // Para solicitudes de reprogramación
            $table->timestamps();
            
            // Índices
            $table->index(['mentor_id', 'status']);
            $table->index(['mentee_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mentorship_sessions');
    }
}
