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
use App\Http\Controllers\TestController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Ruta para el favicon
Route::get('/favicon.ico', function () {
    return response()->file(public_path('favicon.ico'));
})->name('favicon');

// Ruta principal
Route::get('/', function () {
    return view('welcome');
})->name('home')->middleware(['guest']);

// Ruta para la política de privacidad
Route::get('/privacidad', function () {
    return view('privacy-policy');
})->name('privacy.policy');

// Ruta para los términos y condiciones
Route::get('/terminos', function () {
    return view('terms');
})->name('terms');

// Ruta para la política de cookies
Route::get('/cookies', function () {
    return view('cookies');
})->name('cookies');

// Ruta para la declaración de accesibilidad
Route::get('/accesibilidad', function () {
    return view('accessibility');
})->name('accessibility');

// Ruta para las Preguntas Frecuentes (FAQ)
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Ruta para las Guías y Recursos
Route::get('/guias', function () {
    return view('guides');
})->name('guides');

// Ruta para los Tutoriales
Route::get('/tutoriales', function () {
    return view('tutorials');
})->name('tutorials.index');

// Ruta para la Comunidad
Route::get('/comunidad', function () {
    return view('community');
})->name('community');

// Ruta para Eventos
Route::get('/eventos', function () {
    return view('events');
})->name('events');

// Ruta para el Blog
Route::get('/blog', function () {
    return view('blog');
})->name('blog');

// Ruta para acceso de invitados
Route::get('/guest', function () {
    return view('guest');
})->name('guest');

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Página principal
Route::get('/', function () {
    return view('welcome');
})->name('home')->middleware(['guest']);

// Evitar bucle de redirección
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// Test route for checking database structure
Route::get('/test/mentor-sessions', [TestController::class, 'checkTable'])->name('test.mentor-sessions');

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
    
    // Rutas de administración
    Route::prefix('dashboard/admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
        // Dashboard principal
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Rutas de usuarios
        Route::resource('users', AdminUserController::class);
        
        // Rutas de aprobación de mentores
        Route::get('mentors/pending', [AdminUserController::class, 'pendingApprovals'])
            ->name('mentors.pending');
        Route::post('users/{user}/approve', [AdminUserController::class, 'approveMentor'])
            ->name('users.approve');
        Route::post('users/{user}/reject', [AdminUserController::class, 'rejectMentor'])
            ->name('users.reject');
        
        // Rutas de cursos
        Route::resource('courses', AdminCourseController::class);
        
        // Rutas de eventos
        Route::resource('events', AdminEventController::class);
        
        // Rutas de notificaciones
        Route::resource('notifications', AdminNotificationController::class);
        Route::post('notifications/{notification}/send', [AdminNotificationController::class, 'sendNotification'])->name('notifications.send');
        Route::get('notifications/export/{format?}', [AdminNotificationController::class, 'export'])->name('notifications.export');
        
        // Rutas de mensajes
        Route::resource('messages', AdminMessageController::class);
        
        // Rutas de configuración
        Route::prefix('settings')->name('settings.')->group(function () {
            // Configuración general
            Route::get('general', [\App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('general');
            Route::put('general', [\App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('general.update');
            
            // Configuración de notificaciones
            Route::get('notifications', [\App\Http\Controllers\Admin\SettingsController::class, 'notifications'])->name('notifications');
            Route::put('notifications', [\App\Http\Controllers\Admin\SettingsController::class, 'updateNotifications'])->name('notifications.update');
            
            // Configuración de apariencia
            Route::get('appearance', [\App\Http\Controllers\Admin\SettingsController::class, 'appearance'])->name('appearance');
            Route::put('appearance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAppearance'])->name('appearance.update');
            Route::post('appearance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAppearance'])->name('appearance.update.ajax');
            
            // Test de configuración
            Route::get('test', [\App\Http\Controllers\Admin\SettingsController::class, 'testSettings'])->name('test');
            Route::post('test', [\App\Http\Controllers\Admin\SettingsController::class, 'updateTestSetting'])->name('test.update');
        });
    });

    // Rutas de gestión de cursos para el mentor
    Route::middleware(['auth'])->prefix('mentor/courses')->name('mentor.courses.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Mentor\CourseController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Mentor\CourseController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Mentor\CourseController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Mentor\CourseController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Mentor\CourseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Mentor\CourseController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Mentor\CourseController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/students', [\App\Http\Controllers\Mentor\CourseController::class, 'students'])->name('students');
        Route::get('/{id}/statistics', [\App\Http\Controllers\Mentor\CourseController::class, 'statistics'])->name('statistics');
        Route::post('/{id}/duplicate', [\App\Http\Controllers\Mentor\CourseController::class, 'duplicate'])->name('duplicate');

        // Modules for a specific course (Mentor)
        Route::prefix('/{course}/modules')->name('modules.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Mentor\ModuleController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Mentor\ModuleController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Mentor\ModuleController::class, 'store'])->name('store');
            Route::get('/{module}', [\App\Http\Controllers\Mentor\ModuleController::class, 'show'])->name('show');
            Route::get('/{module}/edit', [\App\Http\Controllers\Mentor\ModuleController::class, 'edit'])->name('edit');
            Route::put('/{module}', [\App\Http\Controllers\Mentor\ModuleController::class, 'update'])->name('update');
            Route::delete('/{module}', [\App\Http\Controllers\Mentor\ModuleController::class, 'destroy'])->name('destroy');
            // Optional: Route::post('/reorder', [\App\Http\Controllers\Mentor\ModuleController::class, 'reorder'])->name('reorder');
        });
    });
    
    // Rutas de estudiante - Movidas a routes/student.php
    // Aquí solo mantenemos la redirección al dashboard de estudiante
});

// Rutas del formulario de contacto
Route::get('/contacto', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

// Rutas de administración
require __DIR__.'/admin.php';

// Rutas de cursos, módulos, tutoriales y contenidos
require __DIR__.'/course.php';

// Test route for settings
Route::get('/test-settings', function() {
    // Test setting helper function
    $siteName = setting('site_name', 'Default Site Name');
    
    // Set a test setting
    setting(['test_key' => 'test_value']);
    $testValue = setting('test_key', 'default_value');
    
    // Test blade directive
    $bladeDirective = app('blade.compiler')->compileString("@setting('site_name')");
    
    return [
        'site_name' => $siteName,
        'test_key' => $testValue,
        'blade_directive' => $bladeDirective,
        'all_settings' => app('settings')->all()
    ];
})->name('test.settings');
