<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

// Rutas para administradores
Route::middleware(['auth'])->prefix('dashboard/admin')->name('admin.')->group(function () {
    // Panel principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Gestión de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/export/{format?}', [UserController::class, 'export'])->name('users.export');
    
    // Gestión de roles y permisos
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/permissions', [RoleController::class, 'permissions'])->name('permissions.index');
    Route::post('/permissions/sync', [RoleController::class, 'syncPermissions'])->name('permissions.sync');
    Route::post('/permissions/generate', [RoleController::class, 'generatePermissions'])->name('permissions.generate');
    
    // Gestión de cursos
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    
    // Gestión de eventos
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // Notificaciones
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::get('/notifications/{notification}/edit', [NotificationController::class, 'edit'])->name('notifications.edit');
    Route::put('/notifications/{notification}', [NotificationController::class, 'update'])->name('notifications.update');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/export/{format?}', [NotificationController::class, 'export'])->name('notifications.export');
    
    // Mensajes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages/{message}/edit', [MessageController::class, 'edit'])->name('messages.edit');
    Route::put('/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{message}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
    Route::get('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::get('/messages/{message}/attachment/{attachment}/download', [MessageController::class, 'downloadAttachment'])->name('messages.download');
    Route::delete('/messages/{message}/attachment/{attachment}', [MessageController::class, 'removeAttachment'])->name('messages.remove-attachment');
    
    // Registros de Actividad
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity_logs.show');
    Route::get('/activity-logs/user/{userId}', [ActivityLogController::class, 'userActivity'])->name('activity_logs.user');
    Route::get('/activity-logs/analytics', [ActivityLogController::class, 'analytics'])->name('activity_logs.analytics');
    Route::post('/activity-logs/prune', [ActivityLogController::class, 'prune'])->name('activity_logs.prune');
    Route::post('/activity-logs/settings', [ActivityLogController::class, 'saveSettings'])->name('activity_logs.settings');
    
    // Configuración
    Route::prefix('settings')->name('settings.')->group(function () {
        // Configuración general
        Route::get('/general', [SettingController::class, 'general'])->name('general');
        Route::put('/general', [SettingController::class, 'updateGeneral'])->name('general.update');
        
        // Notificaciones
        Route::get('/notifications', [SettingController::class, 'notifications'])->name('notifications');
        Route::put('/notifications', [SettingController::class, 'updateNotifications'])->name('notifications.update');
        
        // Apariencia
        Route::get('/appearance', [SettingController::class, 'appearance'])->name('appearance');
        Route::put('/appearance', [SettingController::class, 'updateAppearance'])->name('appearance.update');
    });
});
