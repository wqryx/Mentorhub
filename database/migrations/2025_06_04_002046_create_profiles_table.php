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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('preferred_language', 10)->default('es');
            $table->json('social_links')->nullable();
            $table->text('education')->nullable();
            $table->text('experience')->nullable();
            $table->text('skills')->nullable();
            $table->text('interests')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
