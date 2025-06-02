<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;

class NotificationsExport
{
    protected $notifications;

    public function __construct(Collection $notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * Export notifications to Excel file
     *
     * @return string Path to the generated file
     */
    public function export()
    {
        $data = $this->prepareData();
        $filename = 'notifications_' . now()->format('Y-m-d_His') . '.xlsx';
        
        // Define the path within the storage directory
        $storagePath = 'app/exports';
        $fullPath = storage_path($storagePath);
        
        // Ensure the exports directory exists with proper permissions
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
        
        $filePath = $fullPath . '/' . $filename;
        
        // Export the data to Excel
        (new FastExcel($data))->export($filePath);
        
        // Return the full path to the generated file
        return $filePath;
    }
    
    /**
     * Prepare data for export
     */
    protected function prepareData()
    {
        return $this->notifications->map(function($notification) {
            return [
                'ID' => $notification->id,
                'Título' => $notification->title,
                'Mensaje' => $notification->message,
                'Tipo' => ucfirst($notification->type),
                'Pública' => $notification->is_public ? 'Sí' : 'No',
                'Creada por' => $notification->user ? $notification->user->name : 'Sistema',
                'Creada el' => $notification->created_at->format('d/m/Y H:i'),
                'Enviada' => $notification->is_sent ? 'Sí' : 'No',
                'Enviada el' => $notification->sent_at ? $notification->sent_at->format('d/m/Y H:i') : 'No enviada',
                'Total destinatarios' => $notification->users_count ?? $notification->users()->count(),
            ];
        });
    }
}
