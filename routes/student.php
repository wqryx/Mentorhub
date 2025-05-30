<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\Student\CalendarController;
use App\Http\Controllers\Student\TaskController;
use App\Http\Controllers\Student\MessageController;
use Illuminate\Support\Facades\Route;

// Rutas para estudiantes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    // Dashboard y rutas principales
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    
    // Rutas para cursos
    Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
    Route::get('/courses/{id}', [StudentController::class, 'showCourse'])->name('courses.show');
    Route::get('/courses/{course}/progress', [StudentController::class, 'courseProgress'])->name('course.progress');
    
    // Rutas de calendario
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/{id}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
    
    // Rutas de perfil
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::patch('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
    
    // Rutas de notificaciones
    Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all-read', [StudentController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
    
    // Rutas de mensajes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{id}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::post('/messages/{id}/toggle-read', [MessageController::class, 'toggleRead'])->name('messages.toggle-read');
    Route::post('/messages/mark-all-read', [MessageController::class, 'markAllAsRead'])->name('messages.mark-all-read');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
    
    // Rutas de tareas
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/{id}/toggle-status', [TaskController::class, 'toggleStatus'])->name('tasks.toggle-status');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/json', [TaskController::class, 'getTasks'])->name('tasks.json');
    
    // Rutas de calificaciones
    Route::get('/grades', [StudentController::class, 'grades'])->name('grades');
    
    // Rutas para configuración
    Route::get('/settings', [StudentController::class, 'settings'])->name('settings');
});
