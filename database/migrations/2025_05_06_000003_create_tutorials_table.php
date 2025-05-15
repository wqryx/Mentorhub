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
        // Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Create tags table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Create tutorials table
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('reading_time')->nullable();
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
            
            // Add indexes
            $table->index('user_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('is_premium');
        });

        // Create tutorial_tag pivot table
        Schema::create('tutorial_tag', function (Blueprint $table) {
            $table->foreignId('tutorial_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['tutorial_id', 'tag_id']);
        });

        // Create comments table for tutorials
        Schema::create('tutorial_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutorial_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('tutorial_comments')->onDelete('cascade');
            $table->text('content');
            $table->integer('likes')->default(0);
            $table->timestamps();
            
            // Add indexes
            $table->index('tutorial_id');
            $table->index('user_id');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorial_comments');
        Schema::dropIfExists('tutorial_tag');
        Schema::dropIfExists('tutorials');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
    }
};
