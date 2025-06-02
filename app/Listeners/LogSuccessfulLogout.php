<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\App;

class LogSuccessfulLogout
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
    public function handle(Logout $event): void
    {
        if ($event->user) {
            try {
                $service = App::make('activity.logger');
                $service->logLogout($event->user);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error al registrar logout: ' . $e->getMessage());
            }
        }
    }
}
