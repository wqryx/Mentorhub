<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('course')->nullable()->after('password');
            $table->string('cycle')->nullable()->after('course');
            $table->string('photo')->nullable()->after('cycle');
            $table->string('phone')->nullable()->after('photo');
            $table->string('address')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('address');
            $table->string('emergency_contact')->nullable()->after('birth_date');
            $table->string('emergency_phone')->nullable()->after('emergency_contact');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'course',
                'cycle',
                'photo',
                'phone',
                'address',
                'birth_date',
                'emergency_contact',
                'emergency_phone'
            ]);
        });
    }
};
