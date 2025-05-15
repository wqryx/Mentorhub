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
        Schema::table('users', function (Blueprint $table) {
            // Add role relationship and profile fields
            $table->foreignId('role_id')->after('id')->constrained();
            $table->string('username')->unique()->after('name');
            $table->string('bio')->nullable()->after('email');
            $table->string('profile_photo')->nullable()->after('bio');
            $table->string('location')->nullable()->after('profile_photo');
            $table->string('website')->nullable()->after('location');
            $table->boolean('is_verified')->default(false)->after('website');
            $table->timestamp('last_active_at')->nullable()->after('is_verified');
            
            // Add indexes for better performance
            $table->index('username');
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['username']);
            $table->dropIndex(['role_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'role_id',
                'username',
                'bio',
                'profile_photo',
                'location',
                'website',
                'is_verified',
                'last_active_at'
            ]);
        });
    }
};
