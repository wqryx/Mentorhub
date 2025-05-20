<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('credits');
            $table->integer('semester');
            $table->decimal('progress', 5, 2)->default(0);
            $table->integer('pending_tasks')->default(0);
            $table->timestamps();
        });

        Schema::create('module_user', function (Blueprint $table) {
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->datetime('enrollment_date');
            $table->timestamps();
            $table->primary(['module_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('module_user');
        Schema::dropIfExists('modules');
    }
};
