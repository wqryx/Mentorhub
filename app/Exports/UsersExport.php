<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Response;

class UsersExport
{
    /**
     * Get the filename for the export.
     *
     * @return string
     */
    public function getFileName($format = 'csv')
    {
        return 'usuarios-' . now()->format('Y-m-d-His') . '.' . $format;
    }

    /**
     * Export users to CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function toCsv()
    {
        $users = User::with('roles')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $this->getFileName('csv') . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Nombre', 'Email', 'Roles', 'Estado', 
                'Email Verificado', 'Último Acceso', 'Fecha de Creación', 'Última Actualización'
            ]);
            
            // Add user data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->roles->pluck('name')->implode(', '),
                    $user->is_active ? 'Activo' : 'Inactivo',
                    $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : 'No verificado',
                    $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca',
                    $user->created_at->format('d/m/Y H:i'),
                    $user->updated_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}