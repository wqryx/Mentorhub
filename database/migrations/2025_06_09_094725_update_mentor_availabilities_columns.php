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
        Schema::table('mentor_availabilities', function (Blueprint $table) {
            // Rename mentor_id to user_id if it exists
            if (Schema::hasColumn('mentor_availabilities', 'mentor_id')) {
                $table->renameColumn('mentor_id', 'user_id');
            }
            
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('mentor_availabilities', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('end_time');
            }
            
            if (!Schema::hasColumn('mentor_availabilities', 'recurring')) {
                $table->boolean('recurring')->default(true)->after('is_available');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentor_availabilities', function (Blueprint $table) {
            // Revert the column name change if needed
            if (Schema::hasColumn('mentor_availabilities', 'user_id')) {
                $table->renameColumn('user_id', 'mentor_id');
            }
            
            // Drop the columns if they were added
            if (Schema::hasColumn('mentor_availabilities', 'is_available')) {
                $table->dropColumn('is_available');
            }
            
            if (Schema::hasColumn('mentor_availabilities', 'recurring')) {
                $table->dropColumn('recurring');
            }
        });
    }
};
