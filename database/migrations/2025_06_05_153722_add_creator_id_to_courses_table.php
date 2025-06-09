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
        // Step 1: Add the creator_id column as nullable
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('creator_id')->after('id')->nullable()->constrained('users')->onDelete('cascade');
        });

        // Step 2: Populate creator_id
        // Use teacher_id if available and not null
        if (Schema::hasColumn('courses', 'teacher_id')) {
            \Illuminate\Support\Facades\DB::table('courses')
                ->whereNotNull('teacher_id')
                ->update(['creator_id' => \Illuminate\Support\Facades\DB::raw('teacher_id')]);
            
            // For courses where teacher_id was NULL (so creator_id is still NULL after the above),
            // set a default creator_id. Assuming user ID 1 is a safe default.
            // IMPORTANT: Ensure user with ID 1 exists in your 'users' table, or change this default.
            $defaultCreatorId = 1; 
            \Illuminate\Support\Facades\DB::table('courses')
                ->whereNull('creator_id') // This targets rows where teacher_id was NULL or creator_id wasn't set
                ->update(['creator_id' => $defaultCreatorId]);
        } else {
            // Fallback if teacher_id column doesn't exist for some reason.
            // Set all creator_id to a default.
            $defaultCreatorId = 1; 
            \Illuminate\Support\Facades\DB::table('courses')
                ->update(['creator_id' => $defaultCreatorId]);
        }
        
        // At this point, all courses should have a non-null creator_id, 
        // assuming $defaultCreatorId is a valid user ID.

        // Step 3: Make the creator_id column non-nullable
        // Note: Using unsignedBigInteger directly as foreignId() is a macro that sets this up.
        // When changing, we need to be explicit if the type might be inferred differently.
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('creator_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropColumn('creator_id');
        });
    }
};
