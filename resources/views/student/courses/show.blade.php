@extends('student.layouts.app')

@section('title', $course->title . ' - MentorHub')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .module-card {
        transition: all 0.2s ease;
    }
    .module-card:hover {
        transform: translateY(-2px);
    }
    .tutorial-item {
        transition: background-color 0.2s ease;
    }
    .tutorial-item:hover {
        background-color: #f9fafb;
    }
    .progress-bar {
        height: 8px;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Course Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="md:flex">
            <div class="md:flex-shrink-0 md:w-1/3">
                @if($course->image)
                    <img class="w-full h-48 md:h-full object-cover" 
                         src="{{ asset('storage/' . $course->image) }}" 
                         alt="{{ $course->title }}">
                @else
                    <div class="w-full h-48 md:h-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-book-open text-white text-5xl"></i>
                    </div>
                @endif
            </div>
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $course->title }}</h1>
                    @if($course->is_premium)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-crown mr-1"></i> Premium
                        </span>
                    @endif
                </div>
                
                <p class="text-gray-600 mb-6">{{ $course->description }}</p>
                
                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user-graduate mr-2 text-blue-500"></i>
                        <span>{{ $course->instructor->name ?? 'Instructor' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-signal mr-2 text-blue-500"></i>
                        <span>
                            @if($course->level == 'beginner') Principiante
                            @elseif($course->level == 'intermediate') Intermedio
                            @elseif($course->level == 'advanced') Avanzado
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="far fa-clock mr-2 text-blue-500"></i>
                        <span>{{ $course->duration_hours }} horas</span>
                    </div>
                </div>
                
                <!-- Progress -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">Tu progreso</span>
                        <span class="font-medium text-blue-600">{{ $enrollment->progress ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                    </div>
                </div>
                
                <!-- Continue Button -->
                @if($enrollment && $enrollment->last_accessed_tutorial)
                    <a href="{{ route('student.tutorials.show', [$course->id, $enrollment->last_accessed_tutorial->id]) }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-play-circle mr-2"></i> Continuar donde lo dejaste
                    </a>
                @else
                    @php
                        $firstModule = $course->modules->first();
                        $firstTutorial = $firstModule ? $firstModule->tutorials->first() : null;
                    @endphp
                    @if($firstTutorial)
                        <a href="{{ route('student.tutorials.show', [$course->id, $firstTutorial->id]) }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-play mr-2"></i> Comenzar curso
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    <!-- Course Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Contenido del curso</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($course->modules as $module)
                <div class="module-card">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-folder text-blue-600"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $module->title }}</h3>
                            </div>
                            <span class="text-sm text-gray-500">{{ $module->tutorials_count ?? $module->tutorials->count() }} lecciones</span>
                        </div>
                    </div>
                    <div class="bg-gray-50">
                        @forelse($module->tutorials as $tutorial)
                            <a href="{{ route('student.tutorials.show', [$course->id, $tutorial->id]) }}" 
                               class="block tutorial-item px-6 py-4 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                        <i class="fas fa-play text-gray-500 text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $tutorial->title }}</p>
                                        <div class="flex items-center text-xs text-gray-500 mt-1">
                                            <span>{{ $tutorial->duration }} min</span>
                                            @if($tutorial->is_premium)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-crown mr-1"></i> Premium
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($enrollment && $enrollment->completedTutorials->contains($tutorial->id))
                                        <div class="ml-4 flex-shrink-0">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Completado
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay lecciones disponibles en este módulo.
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-gray-500 text-sm font-medium">No hay módulos disponibles</h3>
                    <p class="mt-1 text-sm text-gray-500">Este curso aún no tiene contenido.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Course Resources -->
    @if(($course->resources ?? collect())->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Recursos adicionales</h2>
            </div>
            <div class="bg-white overflow-hidden">
                <ul class="divide-y divide-gray-200">
                    @foreach($course->resources as $resource)
                        <li>
                            <a href="{{ $resource->url }}" target="_blank" class="block hover:bg-gray-50">
                                <div class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            @if(str_contains($resource->type, 'pdf'))
                                                <i class="fas fa-file-pdf text-red-500"></i>
                                            @elseif(str_contains($resource->type, 'word'))
                                                <i class="fas fa-file-word text-blue-600"></i>
                                            @elseif(str_contains($resource->type, 'spreadsheet'))
                                                <i class="fas fa-file-excel text-green-600"></i>
                                            @else
                                                <i class="fas fa-file-alt text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-blue-600 truncate">{{ $resource->title }}</p>
                                            <p class="text-sm text-gray-500">{{ $resource->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    
    <!-- Course Description -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Acerca de este curso</h2>
        </div>
        <div class="px-6 py-4 prose max-w-none">
            {!! $course->long_description !!}
        </div>
    </div>
    
    <!-- Instructor -->
    @if($course->instructor)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Instructor</h2>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <img class="h-16 w-16 rounded-full" 
                             src="{{ $course->instructor->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                             alt="{{ $course->instructor->name }}">
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $course->instructor->name }}</h3>
                        @if($course->instructor->title)
                            <p class="text-sm text-gray-600">{{ $course->instructor->title }}</p>
                        @endif
                        @if($course->instructor->bio)
                            <p class="mt-2 text-sm text-gray-600">{{ $course->instructor->bio }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Initialize any scripts if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Add any initialization code here
    });
</script>
@endpush
