<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();  // Clave única
            $table->text('value');             // Contenido de la caché
            $table->integer('expiration');     // Fecha de expiración
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache');
    }
};
