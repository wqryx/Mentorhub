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
        // Create mentor_profiles table
        Schema::create('mentor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->text('expertise')->nullable();
            $table->text('experience')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('website')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->integer('rating')->default(0);
            $table->integer('sessions_completed')->default(0);
            $table->timestamps();
            
            // Add indexes
            $table->index('user_id');
            $table->index('is_available');
            $table->index('is_verified');
            $table->index('rating');
        });

        // Create mentor_specialties table
        Schema::create('mentor_specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create mentor_specialty pivot table
        Schema::create('mentor_specialty', function (Blueprint $table) {
            $table->foreignId('mentor_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('mentor_specialty_id')->constrained()->onDelete('cascade');
            $table->integer('years_experience')->default(0);
            $table->timestamps();
            
            // Add primary key
            $table->primary(['mentor_profile_id', 'mentor_specialty_id'], 'mentor_specialty_primary');
        });

        // Create mentor_availability table
        Schema::create('mentor_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_profile_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_recurring')->default(true);
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            
            // Add indexes
            $table->index('mentor_profile_id');
            $table->index('day_of_week');
            $table->index('is_recurring');
        });

        // Create mentorship_sessions table
        Schema::create('mentorship_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('goals')->nullable();
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->string('meeting_link')->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
            
            // Add indexes
            $table->index('mentor_profile_id');
            $table->index('student_id');
            $table->index('scheduled_at');
            $table->index('status');
            $table->index('is_paid');
        });

        // Create mentorship_reviews table
        Schema::create('mentorship_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentorship_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentor_profile_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->comment('1-5 star rating');
            $table->text('review')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            
            // Add unique constraint
            $table->unique(['mentorship_session_id', 'student_id']);
            
            // Add indexes
            $table->index('mentor_profile_id');
            $table->index('rating');
        });

        // Create mentorship_agreements table
        Schema::create('mentorship_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->text('goals');
            $table->text('expectations');
            $table->integer('sessions_per_month')->default(1);
            $table->integer('duration_months')->default(3);
            $table->decimal('monthly_price', 8, 2)->default(0.00);
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            
            // Add indexes
            $table->index('mentor_profile_id');
            $table->index('student_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentorship_agreements');
        Schema::dropIfExists('mentorship_reviews');
        Schema::dropIfExists('mentorship_sessions');
        Schema::dropIfExists('mentor_availability');
        Schema::dropIfExists('mentor_specialty');
        Schema::dropIfExists('mentor_specialties');
        Schema::dropIfExists('mentor_profiles');
    }
};
