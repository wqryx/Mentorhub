<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/student', [App\Http\Controllers\DashboardController::class, 'studentDashboard'])->name('dashboard.student');
    Route::get('/dashboard/mentor', [App\Http\Controllers\DashboardController::class, 'mentorDashboard'])->name('dashboard.mentor');
    Route::get('/dashboard/admin', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
});

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Chat routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{chatRoom}', [ChatController::class, 'show'])->name('show');
        Route::post('/{chatRoom}/messages', [ChatController::class, 'sendMessage'])->name('send-message');
        Route::post('/{chatRoom}/read', [ChatController::class, 'markAsRead'])->name('mark-read');
        Route::get('/{chatRoom}/messages', [ChatController::class, 'getMoreMessages'])->name('more-messages');
        Route::post('/private/{user}', [ChatController::class, 'createPrivateChat'])->name('create-private');
        Route::post('/group', [ChatController::class, 'createGroupChat'])->name('create-group');
    });
});

require __DIR__.'/auth.php';
