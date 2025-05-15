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
        // We don't need to modify the users table directly since we're using a pivot table
        // for the many-to-many relationship between users and chat rooms
        
        // This migration just serves as a placeholder to remind us that we need to add
        // a chatRooms() method to the User model
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
