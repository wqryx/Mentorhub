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
        Schema::table('course_user', function (Blueprint $table) {
            $table->integer('progress')->default(0)->after('course_id');
            $table->boolean('completed')->default(false)->after('progress');
            $table->timestamp('enrolled_at')->useCurrent()->after('completed');
            $table->timestamp('completed_at')->nullable()->after('enrolled_at');
            $table->timestamp('last_activity')->nullable()->after('completed_at');
            $table->boolean('is_favorite')->default(false)->after('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->dropColumn([
                'progress',
                'completed',
                'enrolled_at',
                'completed_at',
                'last_activity',
                'is_favorite'
            ]);
        });
    }
};
