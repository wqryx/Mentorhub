<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\App;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        try {
            $service = App::make('activity.logger');
            $service->logLogin($event->user);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al registrar login: ' . $e->getMessage());
        }
    }
}
