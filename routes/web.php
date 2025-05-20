<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de Administrador
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/register', [\App\Http\Controllers\Admin\AdminController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Admin\AdminController::class, 'register']);
    Route::get('/login', [\App\Http\Controllers\Admin\AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('logout');
});

// Ruta para acceso como invitado
Route::get('/guest-login', [\App\Http\Controllers\GuestController::class, 'loginAsGuest'])->name('guest.login');

// Ruta para acceso como invitado
Route::get('/guest-login', [\App\Http\Controllers\GuestController::class, 'loginAsGuest'])->name('guest.login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/student', [App\Http\Controllers\DashboardController::class, 'studentDashboard'])->name('dashboard.student');
    Route::get('/dashboard/mentor', [App\Http\Controllers\DashboardController::class, 'mentorDashboard'])->name('dashboard.mentor');
    Route::get('/dashboard/admin', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
    
    // Rutas para certificados
    Route::get('/certificates', [App\Http\Controllers\CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [App\Http\Controllers\CertificateController::class, 'show'])->name('certificates.show');
    
    // Rutas para documentos
    Route::get('/documents', [App\Http\Controllers\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [App\Http\Controllers\DocumentController::class, 'show'])->name('documents.show');
    Route::post('/documents/upload', [App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');

    // Rutas para inscripciones
    Route::get('/enrollment', [App\Http\Controllers\EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::post('/enrollment/verify', [App\Http\Controllers\EnrollmentController::class, 'verify'])->name('enrollment.verify');
    Route::post('/enrollment/submit', [App\Http\Controllers\EnrollmentController::class, 'submit'])->name('enrollment.submit');
});

// Rutas públicas
Route::get('/help-center', [App\Http\Controllers\HelpCenterController::class, 'index'])->name('help.center');

Route::get('/calendar/sync', [App\Http\Controllers\CalendarController::class, 'sync'])->name('calendar.sync');
Route::get('/calendar/ical', [App\Http\Controllers\CalendarController::class, 'ical'])->name('calendar.ical');

Route::get('/job-portal', function () {
    return view('job-portal.index');
})->name('job.portal');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    
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
