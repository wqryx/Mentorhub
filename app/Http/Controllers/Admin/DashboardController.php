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
        $this->middleware(['auth', 'role:admin']);
    }
    
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = [
            'totalStudents' => User::role('student')->count(),
            'totalMentors' => User::role('mentor')->count(),
            'totalCourses' => Course::count(),
            'recentEnrollments' => Enrollment::with(['user', 'course'])
                ->latest()
                ->take(5)
                ->get(),
            'upcomingEvents' => [], // Agregar lógica para eventos si es necesario
        ];
        
        return view('admin.dashboard', $data);
    }
}
