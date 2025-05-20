<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpArticlesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('help_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('category');
            $table->text('content');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Insertar artículos de ayuda iniciales
        $articles = [
            [
                'title' => 'Cómo usar el Dashboard',
                'category' => 'General',
                'content' => 'Guía paso a paso para navegar por el dashboard...',
                'is_published' => true,
                'published_at' => now()
            ],
            [
                'title' => 'Sistema de Notificaciones',
                'category' => 'Funcionalidades',
                'content' => 'Cómo configurar y usar el sistema de notificaciones...',
                'is_published' => true,
                'published_at' => now()
            ],
            [
                'title' => 'Soporte Técnico',
                'category' => 'Soporte',
                'content' => 'Información sobre cómo contactar al soporte técnico...',
                'is_published' => true,
                'published_at' => now()
            ]
        ];

        foreach ($articles as $article) {
            DB::table('help_articles')->insert($article);
        }

        // Insertar artículos de ayuda iniciales
        $articles = [
            [
                'title' => 'Cómo usar el Dashboard',
                'category' => 'General',
                'content' => 'Guía paso a paso para navegar por el dashboard...',
                'is_published' => true,
                'published_at' => now(),
                'slug' => Str::slug('Como-usar-el-Dashboard')
            ],
            [
                'title' => 'Sistema de Notificaciones',
                'category' => 'Funcionalidades',
                'content' => 'Cómo configurar y usar el sistema de notificaciones...',
                'is_published' => true,
                'published_at' => now(),
                'slug' => Str::slug('Sistema-de-Notificaciones')
            ],
            [
                'title' => 'Soporte Técnico',
                'category' => 'Soporte',
                'content' => 'Información sobre cómo contactar al soporte técnico...',
                'is_published' => true,
                'published_at' => now(),
                'slug' => Str::slug('Soporte-Tecnico')
            ]
        ];

        foreach ($articles as $article) {
            DB::table('help_articles')->insert($article);
        }

        // Crear algunas categorías comunes
        DB::table('help_articles')->insert([
            [
                'title' => 'Cómo usar el Dashboard',
                'category' => 'General',
                'content' => 'Guía paso a paso para navegar por el dashboard...',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Sistema de Notificaciones',
                'category' => 'Funcionalidades',
                'content' => 'Cómo configurar y usar el sistema de notificaciones...',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Soporte Técnico',
                'category' => 'Soporte',
                'content' => 'Información sobre cómo contactar al soporte técnico...',
                'is_published' => true,
                'published_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_articles');
    }
}
