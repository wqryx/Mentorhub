<?php

use App\Http\Controllers\MentorController;
use App\Http\Controllers\Mentor\MentorshipSessionController;
use Illuminate\Support\Facades\Route;

// Rutas para mentores
Route::middleware(['auth', 'verified', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    // Dashboard y perfil
    Route::get('/dashboard', [MentorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MentorController::class, 'profile'])->name('profile');
    Route::patch('/profile', [MentorController::class, 'updateProfile'])->name('profile.update');
    
    // Rutas de mentorías
    Route::prefix('mentorias')->name('mentorias.')->group(function () {
        Route::get('/', [MentorController::class, 'mentorias'])->name('index');
        Route::get('/create', [MentorController::class, 'createMentoria'])->name('create');
        Route::post('/', [MentorController::class, 'storeMentoria'])->name('store');
        Route::get('/{mentoria}', [MentorController::class, 'showMentoria'])->name('show');
        Route::get('/{mentoria}/edit', [MentorController::class, 'editMentoria'])->name('edit');
        Route::put('/{mentoria}', [MentorController::class, 'updateMentoria'])->name('update');
        Route::delete('/{mentoria}', [MentorController::class, 'destroyMentoria'])->name('destroy');
    });
    
    // Rutas de sesiones de mentoría (RESTful)
    Route::resource('sessions', MentorshipSessionController::class)->except(['index']);
    Route::get('sessions', [MentorshipSessionController::class, 'index'])->name('sessions.index');
    
    // Rutas adicionales para sesiones
    Route::prefix('sessions')->name('sessions.')->group(function () {
        // Actualizar estado de la sesión
        Route::put('{session}/status', [MentorshipSessionController::class, 'updateStatus'])
            ->name('update-status');
            
        // Responder a solicitud de mentoría
        Route::post('{session}/respond', [MentorshipSessionController::class, 'respondToRequest'])
            ->name('respond');
            
        // Añadir reseña a la sesión
        Route::post('{session}/review', [MentorshipSessionController::class, 'addReview'])
            ->name('review');
    });
    
    // Otras rutas del mentor
    Route::get('/students', [MentorController::class, 'students'])->name('students');
    Route::get('/students/{id}', [MentorController::class, 'showStudent'])->name('students.show');
    Route::get('/calendar', [MentorController::class, 'calendar'])->name('calendar');
    Route::get('/resources', [MentorController::class, 'resources'])->name('resources');
    Route::get('/notifications', [MentorController::class, 'notifications'])->name('notifications');
    Route::get('/messages', [MentorController::class, 'messages'])->name('messages');
});
