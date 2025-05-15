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
        // Create courses table
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->comment('Course creator/instructor');
            $table->foreignId('category_id')->constrained();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('learning_objectives')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('promotional_video_url')->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->boolean('is_featured')->default(false);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('duration_minutes')->nullable()->comment('Estimated completion time');
            $table->timestamps();
            
            // Add indexes
            $table->index('user_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('difficulty_level');
        });

        // Create modules table
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Add indexes
            $table->index('course_id');
            $table->index('order');
        });

        // Create lessons table
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content');
            $table->string('video_url')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->timestamps();
            
            // Add indexes
            $table->index('module_id');
            $table->index('order');
            $table->index('is_free');
        });

        // Create course_user pivot table for enrollments
        Schema::create('course_user', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('progress', 5, 2)->default(0.00)->comment('Percentage completed');
            $table->primary(['course_id', 'user_id']);
        });

        // Create lesson_user pivot table for tracking lesson completion
        Schema::create('lesson_user', function (Blueprint $table) {
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at')->nullable();
            $table->primary(['lesson_id', 'user_id']);
        });

        // Create course_tag pivot table
        Schema::create('course_tag', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['course_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_tag');
        Schema::dropIfExists('lesson_user');
        Schema::dropIfExists('course_user');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('courses');
    }
};
