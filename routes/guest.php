<?php

use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

// Rutas para invitados (con middleware de roles)
Route::middleware(['auth', 'check.role:invitado'])->prefix('guest')->name('guest.')->group(function () {
    Route::get('/dashboard', [GuestController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [GuestController::class, 'courses'])->name('courses');
    Route::get('/resources', [GuestController::class, 'resources'])->name('resources');
    Route::get('/events', [GuestController::class, 'events'])->name('events');
    Route::get('/calendar', [GuestController::class, 'calendar'])->name('calendar');
    Route::get('/profile', [GuestController::class, 'profile'])->name('profile');
    Route::patch('/profile', [GuestController::class, 'updateProfile'])->name('profile.update');
});
