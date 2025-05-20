<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Update user settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Aquí puedes agregar la lógica para actualizar las configuraciones del usuario
        // Por ejemplo:
        $user->update($request->validate([
            'email' => ['email', 'max:255'],
            'notifications' => ['boolean'],
            // Agrega más campos según tus necesidades
        ]));

        return back()->with('success', 'Configuraciones actualizadas exitosamente');
    }
}
