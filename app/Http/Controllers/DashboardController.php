<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Crear una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el dashboard basado en el tipo de usuario.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Determinar qué vista mostrar basado en el rol del usuario
        if ($user->hasRole('student')) {
            return view('dashboard.student');
        } elseif ($user->hasRole('mentor')) {
            return view('dashboard.mentor');
        } elseif ($user->hasRole('admin')) {
            return view('dashboard.admin');
        } else {
            return view('dashboard.guest');
        }
    }

    /**
     * Mostrar específicamente el dashboard del estudiante.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function studentDashboard()
    {
        return view('dashboard.student');
    }

    /**
     * Mostrar el dashboard del mentor.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function mentorDashboard()
    {
        return view('dashboard.mentor');
    }

    /**
     * Mostrar el dashboard del administrador.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }
}
