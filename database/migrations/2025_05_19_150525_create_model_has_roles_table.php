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
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->morphs('model');
            $table->timestamps();
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // Agregar el valor por defecto para model_type
        DB::statement("ALTER TABLE model_has_roles MODIFY model_type VARCHAR(255) DEFAULT 'App\\Models\\User' NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_roles');
    }
};
