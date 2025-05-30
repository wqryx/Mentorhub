<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Rutas para administradores
Route::middleware(['auth', 'log.activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestión de usuarios
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Gestión de roles y permisos
    Route::resource('roles', RoleController::class);
    Route::get('permissions', [RoleController::class, 'permissions'])->name('permissions.index');
    Route::post('permissions/sync', [RoleController::class, 'syncPermissions'])->name('permissions.sync');
    Route::post('permissions/generate', [RoleController::class, 'generatePermissions'])->name('permissions.generate');
    
    // Gestión de cursos
    Route::get('/courses', [AdminController::class, 'courses'])->name('courses.index');
    Route::get('/courses/create', [AdminController::class, 'createCourse'])->name('courses.create');
    Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');
    Route::get('/courses/{course}/edit', [AdminController::class, 'editCourse'])->name('courses.edit');
    Route::put('/courses/{course}', [AdminController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])->name('courses.destroy');
    
    // Gestión de eventos
    Route::get('/events', [AdminController::class, 'events'])->name('events.index');
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [AdminController::class, 'destroyEvent'])->name('events.destroy');
    
    // Notificaciones y mensajes
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications.index');
    Route::get('/notifications/create', [AdminController::class, 'createNotification'])->name('notifications.create');
    Route::post('/notifications', [AdminController::class, 'storeNotification'])->name('notifications.store');
    Route::get('/notifications/{notification}/edit', [AdminController::class, 'editNotification'])->name('notifications.edit');
    Route::put('/notifications/{notification}', [AdminController::class, 'updateNotification'])->name('notifications.update');
    Route::delete('/notifications/{notification}', [AdminController::class, 'destroyNotification'])->name('notifications.destroy');
    
    // Mensajes
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages.index');
    Route::get('/messages/create', [AdminController::class, 'createMessage'])->name('messages.create');
    Route::post('/messages', [AdminController::class, 'storeMessage'])->name('messages.store');
    Route::get('/messages/{message}/edit', [AdminController::class, 'editMessage'])->name('messages.edit');
    Route::put('/messages/{message}', [AdminController::class, 'updateMessage'])->name('messages.update');
    Route::delete('/messages/{message}', [AdminController::class, 'destroyMessage'])->name('messages.destroy');
    
    // Registros de Actividad
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity_logs.show');
    Route::post('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity_logs.export');
    Route::get('/activity-logs-analytics', [ActivityLogController::class, 'analytics'])->name('activity_logs.analytics');
    Route::post('/activity-logs/prune', [ActivityLogController::class, 'prune'])->name('activity_logs.prune');
    Route::post('/activity-logs/settings', [ActivityLogController::class, 'saveSettings'])->name('activity_logs.settings');
    
    // Configuración
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::patch('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Actividades
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities.index');
});
