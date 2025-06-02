<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // El middleware de roles ha sido eliminado temporalmente
    }
    
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener el tema de la sesión o usar el valor por defecto
        $theme = session('theme', 'light');
        
        // Compartir el tema con todas las vistas
        view()->share('theme', $theme);
        
        $data = [
            'totalStudents' => User::role('student')->count(),
            'totalMentors' => User::role('mentor')->count(),
            'totalCourses' => Course::count(),
            'recentEnrollments' => Enrollment::with(['user', 'course'])
                ->latest()
                ->take(5)
                ->get(),
            'upcomingEvents' => [], // Agregar lógica para eventos si es necesario
            'theme' => $theme, // Pasar el tema a la vista
        ];
        
        return view('admin.dashboard', $data);
    }
}
