<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\ContentController;
use Illuminate\Support\Facades\Route;

// Rutas de cursos
Route::middleware(['auth'])->group(function () {
    // Cursos
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create')->middleware('role:Mentor|Admin');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store')->middleware('role:Mentor|Admin');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit')->middleware('role:Mentor|Admin');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update')->middleware('role:Mentor|Admin');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy')->middleware('role:Mentor|Admin');
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/my-learning', [CourseController::class, 'myLearning'])->name('courses.my-learning');

    // Módulos
    Route::get('/courses/{course}/modules', [ModuleController::class, 'index'])->name('courses.modules.index');
    Route::get('/courses/{course}/modules/create', [ModuleController::class, 'create'])->name('courses.modules.create')->middleware('role:Mentor|Admin');
    Route::post('/courses/{course}/modules', [ModuleController::class, 'store'])->name('courses.modules.store')->middleware('role:Mentor|Admin');
    Route::get('/courses/{course}/modules/{module}', [ModuleController::class, 'show'])->name('courses.modules.show');
    Route::get('/courses/{course}/modules/{module}/edit', [ModuleController::class, 'edit'])->name('courses.modules.edit')->middleware('role:Mentor|Admin');
    Route::put('/courses/{course}/modules/{module}', [ModuleController::class, 'update'])->name('courses.modules.update')->middleware('role:Mentor|Admin');
    Route::delete('/courses/{course}/modules/{module}', [ModuleController::class, 'destroy'])->name('courses.modules.destroy')->middleware('role:Mentor|Admin');
    Route::post('/courses/{course}/modules/reorder', [ModuleController::class, 'reorder'])->name('courses.modules.reorder')->middleware('role:Mentor|Admin');

    // Tutoriales
    Route::get('/courses/{course}/modules/{module}/tutorials', [TutorialController::class, 'index'])->name('courses.modules.tutorials.index');
    Route::get('/courses/{course}/modules/{module}/tutorials/create', [TutorialController::class, 'create'])->name('courses.modules.tutorials.create')->middleware('role:Mentor|Admin');
    Route::post('/courses/{course}/modules/{module}/tutorials', [TutorialController::class, 'store'])->name('courses.modules.tutorials.store')->middleware('role:Mentor|Admin');
    Route::get('/courses/{course}/modules/{module}/tutorials/{tutorial}', [TutorialController::class, 'show'])->name('courses.modules.tutorials.show');
    Route::get('/courses/{course}/modules/{module}/tutorials/{tutorial}/edit', [TutorialController::class, 'edit'])->name('courses.modules.tutorials.edit')->middleware('role:Mentor|Admin');
    Route::put('/courses/{course}/modules/{module}/tutorials/{tutorial}', [TutorialController::class, 'update'])->name('courses.modules.tutorials.update')->middleware('role:Mentor|Admin');
    Route::delete('/courses/{course}/modules/{module}/tutorials/{tutorial}', [TutorialController::class, 'destroy'])->name('courses.modules.tutorials.destroy')->middleware('role:Mentor|Admin');
    Route::post('/courses/{course}/modules/{module}/tutorials/reorder', [TutorialController::class, 'reorder'])->name('courses.modules.tutorials.reorder')->middleware('role:Mentor|Admin');

    // Contenidos
    Route::get('/courses/{course}/modules/{module}/tutorials/{tutorial}/contents', [ContentController::class, 'index'])->name('courses.modules.tutorials.contents.index');
    Route::get('/courses/{course}/modules/{module}/tutorials/{tutorial}/contents/create', [ContentController::class, 'create'])->name('courses.modules.tutorials.contents.create')->middleware('role:Mentor|Admin');
    Route::post('/courses/{course}/modules/{module}/tutorials/{tutorial}/contents', [ContentController::class, 'store'])->name('courses.modules.tutorials.contents.store')->middleware('role:Mentor|Admin');
    Route::get('/courses/{course}/modules/{module}/tutorials/{tutorial}/contents/{content}', [ContentController::class, 'show'])->name('courses.modules.tutorials.contents.show');
    Route::get('/courses/{course}/modules/{module}/tutorials/{tutorial}/contents/{content}/edit', [ContentController::class, 'edit'])->name('courses.modules.tutorials.contents.edit')->middleware('role:Mentor|Admin');
    Route::put('/courses/{course}/modules/{module}/tutorials/{tutorial}/contents/{content}', [ContentController::class, 'update'])->name('courses.modules.tutorials.contents.update')->middleware('role:Mentor|Admin');
    Route::delete('/courses/{course}/modules/{module}/tutorials/{tutorial}/contents/{content}', [ContentController::class, 'destroy'])->name('courses.modules.tutorials.contents.destroy')->middleware('role:Mentor|Admin');
    Route::post('/courses/{course}/modules/{module}/tutorials/{tutorial}/contents/reorder', [ContentController::class, 'reorder'])->name('courses.modules.tutorials.contents.reorder')->middleware('role:Mentor|Admin');
});
