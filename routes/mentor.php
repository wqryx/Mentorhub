<?php

use App\Http\Controllers\MentorController;
use App\Http\Controllers\Mentor\MentorshipSessionController;
use App\Http\Controllers\Mentor\StudentInvitationController;
use Illuminate\Support\Facades\Route;

// Rutas para mentores
Route::middleware(['auth', 'verified'])->prefix('mentor')->name('mentor.')->group(function () {
    // Dashboard y perfil
    Route::get('/dashboard', [MentorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MentorController::class, 'profile'])->name('profile');
    Route::patch('/profile', [MentorController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/photo', [MentorController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    
    // Rutas de mentorías (RESTful)
    Route::resource('mentorias', MentorshipSessionController::class);
    
    // Actualizar disponibilidad
    Route::post('/availability', [MentorController::class, 'updateAvailability'])->name('availability.update');
    
    // Rutas adicionales para mentorías
    Route::prefix('mentorias')->name('mentorias.')->group(function () {
        // Actualizar estado de la mentoría
        Route::put('{mentoria}/status', [MentorshipSessionController::class, 'updateStatus'])
            ->name('update-status');
            
        // Responder a solicitud de mentoría
        Route::post('{mentoria}/respond', [MentorshipSessionController::class, 'respondToRequest'])
            ->name('respond');
            
        // Añadir reseña a la mentoría
        Route::post('{mentoria}/review', [MentorshipSessionController::class, 'addReview'])
            ->name('review');
    });
    
    // Ruta para sessions con su propio controlador
    Route::resource('sessions', \App\Http\Controllers\Mentor\SessionController::class);
    
    // Rutas para gestión de estudiantes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [MentorController::class, 'students'])->name('index');
        Route::get('/invite', [StudentInvitationController::class, 'create'])->name('create');
        Route::post('/invite', [StudentInvitationController::class, 'invite'])->name('store');
        Route::get('/{id}', [MentorController::class, 'showStudent'])->name('show');
    });
    
    // Alias en español para compatibilidad
    Route::get('estudiantes', [MentorController::class, 'students'])->name('students');
    
    // Otras rutas del mentor
    Route::get('/calendar', [MentorController::class, 'calendar'])->name('calendar');
    Route::get('/resources', [MentorController::class, 'resources'])->name('resources');
    Route::get('/notifications', [MentorController::class, 'notifications'])->name('notifications');
    
    // Rutas de mensajería
    Route::get('/messages', [MentorController::class, 'messages'])->name('messages');
    Route::get('/messages/create', [MentorController::class, 'createMessage'])->name('messages.create');
    Route::post('/messages', [MentorController::class, 'sendMessage'])->name('messages.store');
    Route::get('/messages/{conversation}', [MentorController::class, 'showConversation'])->name('messages.show');
    Route::post('/messages/{conversation}/reply', [MentorController::class, 'replyToConversation'])->name('messages.reply');
    
    // Rutas de sesiones agrupadas
    Route::prefix('sessions')->name('sessions.')->group(function () {
        // Iniciar sesión de mentoría
        Route::post('{session}/start', [\App\Http\Controllers\Mentor\SessionController::class, 'start'])
            ->name('start');
            
        // Actualizar estado de la sesión
        Route::put('{session}/status', [\App\Http\Controllers\Mentor\SessionController::class, 'updateStatus'])
            ->name('update-status');
            
        // Responder a solicitud de sesión
        Route::post('{session}/respond', [\App\Http\Controllers\Mentor\SessionController::class, 'respondToRequest'])
            ->name('respond');
            
        // Añadir reseña a la sesión
        Route::post('{session}/review', [\App\Http\Controllers\Mentor\SessionController::class, 'addReview'])
            ->name('review');
    });
});
