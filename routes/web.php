<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Página principal
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Cargar rutas de autenticación desde auth.php
require __DIR__.'/auth.php';

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Redirección según el rol del usuario
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('mentor')) {
            return redirect()->route('mentor.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }
        
        return redirect()->route('home');
    })->name('dashboard');

    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de administrador
    Route::prefix('dashboard/admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        // Panel Resumen
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Gestión de Cursos
        Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
        
        // Gestión de Usuarios
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        
        // Eventos y Calendario
        Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
        
        // Comunicaciones
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        
        // Configuración
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    });
    
    // Rutas de mentor
    Route::prefix('dashboard/mentor')->name('mentor.')->middleware(['role:mentor'])->group(function () {
        // Dashboard principal
        Route::get('/', [MentorDashboardController::class, 'index'])->name('dashboard');
        
        // Cursos del mentor
        Route::get('/my-courses', [MentorDashboardController::class, 'myCourses'])->name('courses');
        
        // Estudiantes asignados
        Route::get('/students', [MentorDashboardController::class, 'students'])->name('students');
        
        // Mensajes
        Route::get('/messages', [MentorDashboardController::class, 'messages'])->name('messages');
        
        // Perfil
        Route::get('/profile', [MentorDashboardController::class, 'profile'])->name('profile');
        
        // Calendario
        Route::get('/calendar', [MentorDashboardController::class, 'calendar'])->name('calendar');
        
        // Recursos
        Route::get('/resources', [MentorDashboardController::class, 'resources'])->name('resources');
        Route::get('/resources/create', [Mentor\ResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [Mentor\ResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{id}', [Mentor\ResourceController::class, 'show'])->name('resources.show');
        Route::get('/resources/{id}/edit', [Mentor\ResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{id}', [Mentor\ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{id}', [Mentor\ResourceController::class, 'destroy'])->name('resources.destroy');
        Route::get('/resources/{id}/download', [Mentor\ResourceController::class, 'download'])->name('resources.download');
        
        // Mentorías
        Route::get('/mentorias', [MentorDashboardController::class, 'mentorias'])->name('mentorias');
        
        // Sesiones de mentoría
        Route::get('/sessions', [Mentor\MentorshipSessionController::class, 'index'])->name('sessions.index');
        Route::get('/sessions/create', [Mentor\MentorshipSessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [Mentor\MentorshipSessionController::class, 'store'])->name('sessions.store');
        Route::get('/sessions/{id}', [Mentor\MentorshipSessionController::class, 'show'])->name('sessions.show');
        Route::get('/sessions/{id}/edit', [Mentor\MentorshipSessionController::class, 'edit'])->name('sessions.edit');
        Route::put('/sessions/{id}', [Mentor\MentorshipSessionController::class, 'update'])->name('sessions.update');
        Route::delete('/sessions/{id}', [Mentor\MentorshipSessionController::class, 'destroy'])->name('sessions.destroy');
        Route::post('/sessions/{id}/status', [Mentor\MentorshipSessionController::class, 'updateStatus'])->name('sessions.status');
        Route::post('/sessions/{id}/respond', [Mentor\MentorshipSessionController::class, 'respondToRequest'])->name('sessions.respond');
        Route::post('/sessions/{id}/review', [Mentor\MentorshipSessionController::class, 'addReview'])->name('sessions.review');
        
        // Gestión de cursos
        Route::get('/courses', [Mentor\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [Mentor\CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [Mentor\CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{id}', [Mentor\CourseController::class, 'show'])->name('courses.show');
        Route::get('/courses/{id}/edit', [Mentor\CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{id}', [Mentor\CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{id}', [Mentor\CourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/courses/{id}/students', [Mentor\CourseController::class, 'students'])->name('courses.students');
        Route::get('/courses/{id}/statistics', [Mentor\CourseController::class, 'statistics'])->name('courses.statistics');
        Route::post('/courses/{id}/duplicate', [Mentor\CourseController::class, 'duplicate'])->name('courses.duplicate');
    });
    
    // Rutas de estudiante
    Route::prefix('dashboard/student')->name('student.')->middleware(['role:student'])->group(function () {
        // Dashboard principal
        Route::get('/', [StudentController::class, 'dashboard'])->name('dashboard');
        
        // Cursos disponibles/asignados
        Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
        
        // Progreso
        Route::get('/progress', [StudentController::class, 'progress'])->name('progress');
        
        // Contactar con mentor
        Route::get('/mentor', [StudentController::class, 'mentor'])->name('mentor');
        
        // Perfil
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        
        // Calendario
        Route::get('/calendar', [StudentController::class, 'calendar'])->name('calendar');
        
        // Tareas
        Route::get('/tasks', [StudentController::class, 'tasks'])->name('tasks');
    });
});

// Rutas de cursos, módulos, tutoriales y contenidos
require __DIR__.'/course.php';
