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
        Schema::table('mentor_sessions', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('mentor_sessions', 'type')) {
                $table->string('type')->default('one_time')->after('meeting_link');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'format')) {
                $table->string('format')->default('video_call')->after('type');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'student_goals')) {
                $table->json('student_goals')->nullable()->after('format');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'mentor_notes')) {
                $table->text('mentor_notes')->nullable()->after('student_goals');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'outcome_summary')) {
                $table->text('outcome_summary')->nullable()->after('mentor_notes');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'cancellation_reason')) {
                $table->string('cancellation_reason')->nullable()->after('outcome_summary');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'is_recurring')) {
                $table->boolean('is_recurring')->default(false)->after('cancellation_reason');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'recurrence_pattern')) {
                $table->string('recurrence_pattern')->nullable()->after('is_recurring');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'session_fee')) {
                $table->decimal('session_fee', 10, 2)->default(0)->after('recurrence_pattern');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('session_fee');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('is_paid');
            }
            
            if (!Schema::hasColumn('mentor_sessions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            }
            
            // Modify existing columns if needed
            if (Schema::hasColumn('mentor_sessions', 'status')) {
                $table->string('status')->default('scheduled')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentor_sessions', function (Blueprint $table) {
            // Drop the columns we added
            $table->dropColumn([
                'type',
                'format',
                'student_goals',
                'mentor_notes',
                'outcome_summary',
                'cancellation_reason',
                'is_recurring',
                'recurrence_pattern',
                'session_fee',
                'is_paid',
                'completed_at',
                'cancelled_at',
            ]);
            
            // Revert status column changes if needed
            $table->string('status')->default('scheduled')->change();
        });
    }
};
