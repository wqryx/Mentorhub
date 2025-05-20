<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->decimal('grade', 5, 2);
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->datetime('date');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
};
