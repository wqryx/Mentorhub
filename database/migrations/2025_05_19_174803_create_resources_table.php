<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('url');
            $table->text('description')->nullable();
            $table->morphs('resourceable');
            $table->timestamps();
        });

        Schema::create('module_resource', function (Blueprint $table) {
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->primary(['module_id', 'resource_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('module_resource');
        Schema::dropIfExists('resources');
    }
};
