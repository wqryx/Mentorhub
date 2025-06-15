@extends('student.layouts.app')

@section('title', 'Mis Cursos - MentorHub')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .course-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        @apply bg-white rounded-lg overflow-hidden shadow-sm border border-gray-200;
    }
    .course-card:hover {
        transform: translateY(-4px);
        @apply shadow-md;
    }
    .course-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            <button type="button" class="ml-4 text-green-500 hover:text-green-700 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl font-bold text-gray-800">Mis Cursos</h1>
            <p class="text-gray-600">Gestiona tu aprendizaje y progreso</p>
        </div>
        <a href="{{ route('student.courses.available') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <i class="fas fa-search mr-2"></i> Explorar más cursos
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Filtros de búsqueda</h2>
            <p class="text-sm text-gray-500">Filtra tus cursos por estado u ordena según tus preferencias</p>
        </div>
        <div class="p-6">
            <form action="{{ route('student.courses') }}" method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-3 md:gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Todos los estados</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En progreso</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completados</option>
                        <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>No iniciados</option>
                    </select>
                </div>
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
                    <select id="sort" name="sort" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Más recientes</option>
                        <option value="progress" {{ request('sort') == 'progress' ? 'selected' : '' }}>Mayor progreso</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Título (A-Z)</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2"></i> Aplicar filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- In Progress Courses -->
    @if($inProgressCourses->count() > 0)
    <div class="mb-8">
        <div class="flex items-center mb-6">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 mr-3">
                <i class="fas fa-clock-rotate-left"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Continúa aprendiendo</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($inProgressCourses as $enrollment)
            <div class="course-card">
                <!-- Course Image -->
                <div class="relative">
                    @if($enrollment->course->image)
                        <img src="{{ asset('storage/' . $enrollment->course->image) }}" 
                             alt="{{ $enrollment->course->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-book-open text-white text-4xl"></i>
                        </div>
                    @endif
                    
                    @if($enrollment->course->is_premium)
                        <span class="absolute top-3 right-3 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                            <i class="fas fa-crown mr-1"></i> Premium
                        </span>
                    @endif
                    
                    <!-- Course Badges -->
                    <div class="absolute bottom-3 left-0 w-full px-4 flex flex-wrap gap-2">
                        @if($enrollment->course->level)
                            <span class="bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full flex items-center">
                                <i class="fas fa-signal text-xs mr-1"></i>
                                @if($enrollment->course->level == 'beginner') Principiante
                                @elseif($enrollment->course->level == 'intermediate') Intermedio
                                @elseif($enrollment->course->level == 'advanced') Avanzado
                                @endif
                            </span>
                        @endif
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-3 py-1 rounded-full flex items-center">
                            <i class="far fa-clock text-xs mr-1"></i> {{ $enrollment->course->duration_hours }}h
                        </span>
                    </div>
                </div>
                
                <!-- Course Content -->
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $enrollment->course->title }}</h3>
                    
                    <!-- Course Stats -->
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <div class="flex items-center mr-4">
                            <i class="fas fa-book text-blue-500 mr-1"></i>
                            <span>{{ $enrollment->course->modules_count ?? $enrollment->course->modules->count() }} módulos</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-video text-blue-500 mr-1"></i>
                            <span>{{ $enrollment->course->modules->sum('tutorials_count') ?? $enrollment->course->modules->sum(function($module) { return $module->tutorials->count(); }) }} lecciones</span>
                        </div>
                    </div>
                    
                    <!-- Progress -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progreso</span>
                            <span class="font-medium">{{ $enrollment->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full {{ $enrollment->progress == 100 ? '!bg-green-500' : '' }}" 
                                 style="width: {{ $enrollment->progress }}%">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Last Activity -->
                    @if($enrollment->last_activity_at)
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <i class="far fa-clock text-blue-500 mr-2"></i>
                            <span>Último acceso: {{ $enrollment->last_activity_at->diffForHumans() }}</span>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-between mt-4">
                        @if($enrollment->currentTutorial)
                            <a href="{{ route('courses.modules.tutorials.show', [
                                'course' => $enrollment->course->id,
                                'module' => $enrollment->currentTutorial->module_id,
                                'tutorial' => $enrollment->currentTutorial->id
                            ]) }}" 
                               class="flex-1 mr-2 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-play-circle mr-2"></i> Continuar
                            </a>
                        @else
                            <a href="{{ route('courses.show', $enrollment->course->id) }}" 
                               class="flex-1 mr-2 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-play-circle mr-2"></i> Comenzar
                            </a>
                        @endif
                        <a href="{{ route('courses.show', $enrollment->course->id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-info-circle mr-2"></i> Detalles
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- All My Courses -->
    <div class="mb-8">
        <div class="flex items-center mb-6">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 mr-3">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Todos mis cursos</h2>
        </div>
        
        @if($enrollments->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-8 text-center">
                    <div class="text-gray-300 mb-4">
                        <i class="fas fa-book-open text-5xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">¡Aún no estás inscrito en ningún curso!</h3>
                    <p class="text-gray-500 mb-6">Explora nuestro catálogo de cursos y comienza tu aprendizaje hoy mismo.</p>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i> Explorar cursos
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrollments as $enrollment)
                <div class="course-card">
                    <!-- Course Image -->
                    <div class="relative">
                        @php
                            $course = $enrollment->course;
                        @endphp
                        
                        @if(!$course)
                            <div class="w-full h-48 bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white text-4xl"></i>
                            </div>
                            <span class="absolute top-3 right-3 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> No disponible
                            </span>
                        @else
                            @if($course->image)
                                <img src="{{ asset('storage/' . $course->image) }}" 
                                     class="w-full h-48 object-cover" 
                                     alt="{{ $course->title }}">
                            @else
                                <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-book-open text-white text-4xl"></i>
                                </div>
                            @endif
                            
                            @if($course->is_premium)
                                <span class="absolute top-3 right-3 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                                    <i class="fas fa-crown mr-1"></i> Premium
                                </span>
                            @endif
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3">
                            @if($enrollment->progress == 100)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                                    <i class="fas fa-check-circle mr-1"></i> Completado
                                </span>
                            @elseif($enrollment->progress > 0)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                                    <i class="fas fa-spinner mr-1 animate-spin"></i> En progreso
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                                    <i class="far fa-clock mr-1"></i> No iniciado
                                </span>
                            @endif
                        </div>
                        
                        <!-- Course Badges -->
                        <div class="absolute bottom-3 left-0 w-full px-4 flex flex-wrap gap-2">
                            @if($enrollment->course->level)
                                <span class="bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full flex items-center">
                                    <i class="fas fa-signal text-xs mr-1"></i>
                                    @if($enrollment->course->level == 'beginner') Principiante
                                    @elseif($enrollment->course->level == 'intermediate') Intermedio
                                    @elseif($enrollment->course->level == 'advanced') Avanzado
                                    @endif
                                </span>
                            @endif
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-3 py-1 rounded-full flex items-center">
                                <i class="far fa-clock text-xs mr-1"></i> {{ $enrollment->course->duration_hours }}h
                            </span>
                        </div>
                    </div>
                    
                    <!-- Course Content -->
                    <div class="p-5">
                        @if(!$course)
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                Curso no disponible
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">
                                Este curso ya no está disponible en la plataforma.
                            </p>
                        @else
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $course->title }}
                            </h3>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                @if($course->instructor)
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tie mr-1 text-blue-500"></i>
                                        <span>{{ $course->instructor->name }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tie mr-1 text-gray-400"></i>
                                        <span class="text-gray-400">Instructor no disponible</span>
                                    </div>
                                @endif
                                <span class="mx-2">•</span>
                                <div class="flex items-center">
                                    <i class="far fa-clock mr-1"></i>
                                    <span>{{ $course->duration ?? '--' }} horas</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($course)
                        <!-- Progress -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Progreso</span>
                                <span>{{ $enrollment->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="px-5 pb-5">
                        <div class="flex justify-between">
                            @if(!$course)
                                <div class="w-full">
                                    <div class="flex items-center p-3 bg-red-50 rounded-md border border-red-200">
                                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                        <span class="text-sm text-red-700">Este curso ya no está disponible</span>
                                    </div>
                                    <div class="mt-3">
                                        <button onclick="event.preventDefault(); document.getElementById('unenroll-form-{{ $enrollment->id }}').submit();" 
                                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <i class="fas fa-times-circle mr-2"></i> Eliminar de mi lista
                                        </button>
                                        <form id="unenroll-form-{{ $enrollment->id }}" action="{{ route('student.courses.unenroll', $enrollment->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="w-full">
                                    <a href="{{ route('student.courses.show', $course->id) }}" class="w-full mb-2 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <i class="fas fa-eye mr-2"></i> Ver detalles del curso
                                    </a>
                                    
                                    @if($enrollment->progress > 0)
                                        <div class="flex space-x-2">
                                            <a href="{{ route('student.courses.progress', $course->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <i class="fas fa-chart-line mr-2"></i> Progreso
                                            </a>
                                            @if($enrollment->progress < 100)
                                                <a href="{{ route('student.courses.resume', $course->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <i class="fas fa-play mr-2"></i> Continuar
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            

                <!-- Pagination -->
                @if($enrollments->hasPages())
                    <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6 mt-8">
                        <div class="flex flex-1 justify-between sm:hidden">
                            @if($enrollments->onFirstPage())
                                <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-300 cursor-not-allowed">
                                    <i class="fas fa-chevron-left mr-2"></i> Anterior
                                </span>
                            @else
                                <a href="{{ $enrollments->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-chevron-left mr-2"></i> Anterior
                                </a>
                            @endif
                            
                            @if($enrollments->hasMorePages())
                                <a href="{{ $enrollments->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Siguiente <i class="fas fa-chevron-right ml-2"></i>
                                </a>
                            @else
                                <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-300 cursor-not-allowed">
                                    Siguiente <i class="fas fa-chevron-right ml-2"></i>
                                </span>
                            @endif
                        </div>
                        
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Mostrando
                                    <span class="font-medium">{{ $enrollments->firstItem() }}</span>
                                    a
                                    <span class="font-medium">{{ $enrollments->lastItem() }}</span>
                                    de
                                    <span class="font-medium">{{ $enrollments->total() }}</span>
                                    resultados
                                </p>
                            </div>
                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if($enrollments->onFirstPage())
                                        <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Anterior</span>
                                            <i class="fas fa-chevron-left h-5 w-5"></i>
                                        </span>
                                    @else
                                        <a href="{{ $enrollments->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Anterior</span>
                                            <i class="fas fa-chevron-left h-5 w-5"></i>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $current = $enrollments->currentPage();
                                        $last = $enrollments->lastPage();
                                        $delta = 2;
                                        $left = $current - $delta;
                                        $right = $current + $delta + 1;
                                        $range = [];
                                        $rangeWithDots = [];
                                        $l = null;
                                    @endphp

                                    @for($i = 1; $i <= $last; $i++)
                                        @if($i == 1 || $i == $last || ($i >= $left && $i < $right))
                                            @php array_push($range, $i); @endphp
                                        @endif
                                    @endfor

                                    @foreach($range as $i)
                                        @if($l)
                                            @if($i - $l === 2)
                                                @php array_push($rangeWithDots, $l + 1); @endphp
                                            @elseif($i - $l !== 1)
                                                @php array_push($rangeWithDots, '...'); @endphp
                                            @endif
                                        @endif
                                        @php 
                                            array_push($rangeWithDots, $i);
                                            $l = $i;
                                        @endphp
                                    @endforeach

                                    @foreach($rangeWithDots as $i)
                                        @if($i === '...')
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">
                                                {{ $i }}
                                            </span>
                                        @elseif($i == $current)
                                            <span aria-current="page" class="relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                                {{ $i }}
                                            </span>
                                        @else
                                            <a href="{{ $enrollments->url($i) }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                {{ $i }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if($enrollments->hasMorePages())
                                        <a href="{{ $enrollments->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Siguiente</span>
                                            <i class="fas fa-chevron-right h-5 w-5"></i>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0">
                                            <span class="sr-only">Siguiente</span>
                                            <i class="fas fa-chevron-right h-5 w-5"></i>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
