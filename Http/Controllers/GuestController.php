<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    public function loginAsGuest()
    {
        // Crear un usuario temporal con rol de invitado
        $user = Auth::loginUsingId(0); // Usamos ID 0 para el usuario invitado
        
        // Redirigir al dashboard
        return redirect()->route('dashboard');
    }
}
