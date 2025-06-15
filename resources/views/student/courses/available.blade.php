@extends('student.layouts.app')

@section('title', 'Cursos Disponibles - MentorHub')

@push('styles')
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
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl font-bold text-gray-800">Cursos Disponibles</h1>
            <p class="text-gray-600">Explora y descubre nuevos cursos para mejorar tus habilidades</p>
        </div>
        <a href="{{ route('student.courses') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver a mis cursos
        </a>
    </div>

    <!-- Available Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($availableCourses as $course)
            <div class="course-card">
                <!-- Course Image -->
                <div class="relative">
                    @if($course->image_path)
                        <img src="{{ asset('storage/' . $course->image_path) }}" 
                             alt="{{ $course->name }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-book text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    @if($course->is_premium)
                        <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded">
                            Premium
                        </span>
                    @endif
                </div>
                
                <!-- Course Info -->
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $course->name }}</h3>
                    
                    <!-- Instructor -->
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <i class="fas fa-user-tie mr-2"></i>
                        <span>{{ $course->instructor->name ?? 'Instructor no asignado' }}</span>
                    </div>
                    
                    <!-- Category -->
                    @if($course->category)
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-3">
                            {{ $course->category->name }}
                        </span>
                    @endif
                    
                    <!-- Description -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                        {{ $course->description ?? 'Sin descripción disponible.' }}
                    </p>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">
                            {{ $course->students_count ?? 0 }} estudiantes
                        </span>
                        
                        <a href="{{ route('student.courses.show', $course->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Ver detalles
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">No hay cursos disponibles en este momento.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($availableCourses->hasPages())
        <div class="mt-8">
            {{ $availableCourses->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
        
        tooltipTriggers.forEach(trigger => {
            new bootstrap.Tooltip(trigger);
        });
    });
</script>
@endpush
