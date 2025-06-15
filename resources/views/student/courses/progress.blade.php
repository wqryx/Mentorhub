@extends('student.layouts.app')

@section('title', 'Progreso del Curso - ' . $course->title . ' - MentorHub')

@push('styles')
<style>
    .progress-thin {
        height: 6px;
    }
    .module-card {
        transition: all 0.3s ease;
        border-left: 4px solid #4f46e5;
    }
    .module-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .activity-item {
        position: relative;
        padding-left: 2rem;
        border-left: 2px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    .activity-item:last-child {
        margin-bottom: 0;
    }
    .activity-dot {
        position: absolute;
        left: -8px;
        top: 4px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $course->title }}</h1>
            <p class="text-gray-600 mt-1">Sigue tu progreso en este curso</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-eye mr-2"></i> Ver curso
            </a>
            <a href="{{ route('student.courses') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Volver a mis cursos
            </a>
        </div>
    </div>

    <!-- Progress Overview -->
    <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Progress -->
                <div class="md:col-span-2">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Progreso general del curso</h3>
                    <div class="mb-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progreso total</span>
                            <span class="font-medium">{{ $enrollment->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                        </div>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-4 bg-indigo-50 rounded-lg text-center">
                            <div class="text-2xl font-bold text-indigo-700">{{ $moduleProgress['completed'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Módulos completados</div>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-700">{{ $tutorialProgress['completed'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Tutoriales completados</div>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-700">{{ $quizProgress['passed'] ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Quizzes aprobados</div>
                        </div>
                    </div>
                </div>
                
                <!-- Course Info -->
                <div class="border-l border-gray-200 pl-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Información del curso</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-book text-indigo-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">{{ $course->modules->count() }} módulos</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-video text-indigo-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">{{ $course->modules->flatMap->tutorials->count() }} tutoriales</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-clock text-indigo-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">Duración: {{ $course->duration_hours ?? 0 }} horas</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-graduation-cap text-indigo-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">Nivel: 
                                @if($course->level == 'beginner')
                                    Principiante
                                @elseif($course->level == 'intermediate')
                                    Intermedio
                                @elseif($course->level == 'advanced')
                                    Avanzado
                                @else
                                    {{ $course->level }}
                                @endif
                            </span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-calendar-alt text-indigo-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">Inscrito el: {{ $enrollment->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Módulos del curso</h2>
            <span class="text-sm text-gray-500">{{ $course->modules->count() }} módulos</span>
        </div>
        
        <div class="space-y-4">
            @forelse($course->modules->sortBy('order') as $module)
                @php
                    $moduleProgressPercent = $module->progress ?? 0;
                    $tutorialsCount = $module->tutorials->count();
                    $completedTutorials = $module->tutorials->filter(function($tutorial) {
                        // Add your completion logic here
                        return false;
                    })->count();
                @endphp
                
                <div class="module-card bg-white rounded-lg shadow overflow-hidden" x-data="{ open: false }">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-800 text-sm font-medium mr-3">
                                        {{ $module->order }}
                                    </span>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $module->name }}</h3>
                                </div>
                                
                                @if($module->description)
                                    <p class="mt-2 text-gray-600">{{ $module->description }}</p>
                                @endif
                                
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Progreso del módulo</span>
                                        <span class="font-medium">{{ $moduleProgressPercent }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $moduleProgressPercent }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 flex items-center text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class="far fa-file-alt mr-1"></i>
                                        {{ $tutorialsCount }} tutoriales
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span class="flex items-center">
                                        <i class="far fa-check-circle mr-1"></i>
                                        {{ $completedTutorials }} de {{ $tutorialsCount }} completados
                                    </span>
                                </div>
                            </div>
                            
                            <button 
                                @click="open = !open" 
                                class="text-indigo-600 hover:text-indigo-900 focus:outline-none"
                                :aria-expanded="open"
                                :aria-controls="'module-{{ $module->id }}'"
                            >
                                <i class="fas" :class="{ 'fa-chevron-down': !open, 'fa-chevron-up': open }"></i>
                            </button>
                        </div>
                        
                        <!-- Tutorials List -->
                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="mt-6 pt-6 border-t border-gray-200"
                            id="module-{{ $module->id }}"
                        >
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Tutoriales</h4>
                            
                            <div class="space-y-3">
                                @forelse($module->tutorials->sortBy('order') as $tutorial)
                                    @php
                                        $isCompleted = false; // Add your completion logic here
                                        $statusClass = $isCompleted ? 'text-green-600' : 'text-gray-400';
                                        $statusText = $isCompleted ? 'Completado' : 'Pendiente';
                                        $icon = $isCompleted ? 'check-circle' : 'circle';
                                    @endphp
                                    
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-150">
                                        <div class="flex items-center">
                                            <i class="far fa-{{ $icon }} {{ $statusClass }} mr-3 text-lg"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $tutorial->title }}</p>
                                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                                    <span class="flex items-center mr-3">
                                                        <i class="fas fa-{{ $tutorial->type === 'video' ? 'play' : 'file-alt' }} text-xs mr-1"></i>
                                                        {{ ucfirst($tutorial->type) }}
                                                    </span>
                                                    @if($tutorial->duration)
                                                        <span class="flex items-center">
                                                            <i class="far fa-clock text-xs mr-1"></i>
                                                            {{ $tutorial->duration }} min
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <a 
                                            href="{{ route('student.tutorials.show', [$course->id, $tutorial->id]) }}" 
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        >
                                            {{ $isCompleted ? 'Ver de nuevo' : 'Comenzar' }}
                                            <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                        </a>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-gray-500">
                                        <i class="far fa-folder-open text-2xl mb-2"></i>
                                        <p>No hay tutoriales en este módulo.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 px-4 bg-white rounded-lg shadow">
                        <i class="fas fa-book-open text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">No hay módulos disponibles en este curso.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Actividad reciente</h2>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                @if(isset($recentActivities) && (is_countable($recentActivities) ? count($recentActivities) : 0) > 0)
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($recentActivities as $index => $activity)
                                @php
                                    $activityIcons = [
                                        'tutorial_completed' => ['icon' => 'check-circle', 'color' => 'text-green-500'],
                                        'quiz_completed' => ['icon' => 'tasks', 'color' => 'text-blue-500'],
                                        'module_completed' => ['icon' => 'flag-checkered', 'color' => 'text-indigo-500'],
                                        'default' => ['icon' => 'info-circle', 'color' => 'text-gray-400']
                                    ];
                                    
                                    $activityType = $activityIcons[$activity->type] ?? $activityIcons['default'];
                                    $activityTitle = '';
                                    
                                    if ($activity->subject_type === 'App\\Models\\Tutorial') {
                                        $activityTitle = $activity->subject->title ?? 'Tutorial';
                                    } elseif ($activity->subject_type === 'App\\Models\\Quiz') {
                                        $activityTitle = 'Quiz: ' . ($activity->subject->title ?? 'Quiz');
                                    } elseif ($activity->subject_type === 'App\\Models\\Module') {
                                        $activityTitle = 'Módulo: ' . ($activity->subject->name ?? 'Módulo');
                                    } else {
                                        $activityTitle = $activity->description ?? 'Nueva actividad';
                                    }
                                    
                                    $isLast = $loop->last;
                                @endphp
                                
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$isLast)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $activityType['color'] }}">
                                                    <i class="fas fa-{{ $activityType['icon'] }} text-lg"></i>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-700">
                                                        {{ $activityTitle }}
                                                        @if($activity->type === 'quiz_completed' && isset($activity->properties['score']))
                                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                Puntuación: {{ $activity->properties['score'] }}/{{ $activity->properties['max_score'] ?? 'N/A' }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    <time datetime="{{ $activity->created_at->toIso8601String() }}">
                                                        {{ $activity->created_at->diffForHumans() }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $activity->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500">No hay actividad reciente para mostrar</p>
                        <p class="text-sm text-gray-400 mt-2">Tu actividad se mostrará aquí a medida que avances en el curso.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Initialize any Alpine.js components here if needed
    });
</script>
@endpush


