<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

const HOME = '/empresas';

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No necesitamos un binding personalizado para Role
        // Usamos la configuración estándar de Spatie
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
