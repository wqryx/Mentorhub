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
        // Create forums table
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_private')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Add indexes
            $table->index('is_private');
            $table->index('order');
        });

        // Create threads table
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->boolean('is_sticky')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('views')->default(0);
            $table->timestamp('last_activity_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            
            // Add indexes
            $table->index('forum_id');
            $table->index('user_id');
            $table->index('is_sticky');
            $table->index('is_locked');
            $table->index('last_activity_at');
        });

        // Create thread_replies table
        Schema::create('thread_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('thread_replies')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_solution')->default(false);
            $table->integer('likes')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Add indexes
            $table->index('thread_id');
            $table->index('user_id');
            $table->index('parent_id');
            $table->index('is_solution');
        });

        // Create thread_subscriptions table
        Schema::create('thread_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Add unique constraint
            $table->unique(['thread_id', 'user_id']);
        });

        // Create forum_user pivot table for moderators
        Schema::create('forum_user', function (Blueprint $table) {
            $table->foreignId('forum_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['moderator', 'contributor'])->default('contributor');
            $table->timestamps();
            
            // Add primary key
            $table->primary(['forum_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_user');
        Schema::dropIfExists('thread_subscriptions');
        Schema::dropIfExists('thread_replies');
        Schema::dropIfExists('threads');
        Schema::dropIfExists('forums');
    }
};
