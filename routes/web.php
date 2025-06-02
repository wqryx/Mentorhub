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
use App\Http\Controllers\StudentDashboardController as CustomStudentDashboardController;
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
    
    // NOTA: Las rutas de administrador han sido movidas a routes/admin.php para evitar duplicación
    // Las siguientes rutas están comentadas porque estaban causando conflictos con las definidas en routes/admin.php
    /*
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
    */
    
    // Rutas de mentor
    Route::prefix('dashboard/mentor')->name('mentor.')->middleware(['role:mentor'])->group(function () {
        // Dashboard principal
        Route::get('/', [MentorDashboardController::class, 'index'])->name('dashboard');
        
        // Cursos del mentor
        Route::get('/courses', [MentorDashboardController::class, 'myCourses'])->name('courses');
        
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
        Route::get('/resources/create', [\App\Http\Controllers\Mentor\ResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [\App\Http\Controllers\Mentor\ResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{id}', [\App\Http\Controllers\Mentor\ResourceController::class, 'show'])->name('resources.show');
        Route::get('/resources/{id}/edit', [\App\Http\Controllers\Mentor\ResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{id}', [\App\Http\Controllers\Mentor\ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{id}', [\App\Http\Controllers\Mentor\ResourceController::class, 'destroy'])->name('resources.destroy');
        Route::get('/resources/{id}/download', [\App\Http\Controllers\Mentor\ResourceController::class, 'download'])->name('resources.download');
        
        // Mentorías
        Route::get('/mentorias', [MentorDashboardController::class, 'mentorias'])->name('mentorias');
        
        // Sesiones de mentoría
        Route::get('/sessions', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'index'])->name('sessions.index');
        Route::get('/sessions/create', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'store'])->name('sessions.store');
        Route::get('/sessions/{id}', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'show'])->name('sessions.show');
        Route::get('/sessions/{id}/edit', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'edit'])->name('sessions.edit');
        Route::put('/sessions/{id}', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'update'])->name('sessions.update');
        Route::delete('/sessions/{id}', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'destroy'])->name('sessions.destroy');
        Route::post('/sessions/{id}/status', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'updateStatus'])->name('sessions.status');
        Route::post('/sessions/{id}/respond', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'respondToRequest'])->name('sessions.respond');
        Route::post('/sessions/{id}/review', [\App\Http\Controllers\Mentor\MentorshipSessionController::class, 'addReview'])->name('sessions.review');
        
        // Gestión de cursos - rutas detalladas
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/create', [\App\Http\Controllers\Mentor\CourseController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Mentor\CourseController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Mentor\CourseController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Mentor\CourseController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Mentor\CourseController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Mentor\CourseController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/students', [\App\Http\Controllers\Mentor\CourseController::class, 'students'])->name('students');
            Route::get('/{id}/statistics', [\App\Http\Controllers\Mentor\CourseController::class, 'statistics'])->name('statistics');
            Route::post('/{id}/duplicate', [\App\Http\Controllers\Mentor\CourseController::class, 'duplicate'])->name('duplicate');
        });
    });
    
    // Rutas de estudiante - Movidas a routes/student.php
    // Aquí solo mantenemos la redirección al dashboard de estudiante
});

// Rutas de cursos, módulos, tutoriales y contenidos
require __DIR__.'/course.php';
