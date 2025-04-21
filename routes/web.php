<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Bloquear registro público
Route::get('/register', function () {
    abort(403, 'Solo el administrador puede registrar usuarios.');
});

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Empresas
    Route::resource('empresas', EmpresaController::class);

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas solo para el admin
    Route::resource('usuarios', UserController::class)
         ->middleware('admin');  // Aquí se aplica el middleware 'admin' directamente
});



require __DIR__.'/auth.php';
