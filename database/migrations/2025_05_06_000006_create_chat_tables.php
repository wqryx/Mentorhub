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
        // Create chat_rooms table
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('type', ['private', 'group'])->default('private');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add indexes
            $table->index('type');
            $table->index('is_active');
        });

        // Create chat_room_user pivot table
        Schema::create('chat_room_user', function (Blueprint $table) {
            $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['owner', 'admin', 'member'])->default('member');
            $table->boolean('is_muted')->default(false);
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
            
            // Add primary key
            $table->primary(['chat_room_id', 'user_id']);
        });

        // Create chat_messages table
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->string('attachment')->nullable();
            $table->string('attachment_type')->nullable();
            $table->boolean('is_system_message')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Add indexes
            $table->index('chat_room_id');
            $table->index('user_id');
            $table->index('created_at');
            $table->index('is_system_message');
        });

        // Create chat_message_reactions table
        Schema::create('chat_message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_message_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reaction', 20);
            $table->timestamps();
            
            // Add unique constraint
            $table->unique(['chat_message_id', 'user_id', 'reaction']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_message_reactions');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_room_user');
        Schema::dropIfExists('chat_rooms');
    }
};
