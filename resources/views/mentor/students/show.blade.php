@extends('layouts.mentor')

@section('title', 'Perfil de Estudiante - ' . $student->name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
@endpush

@section('content')
<!-- Notification Component -->
<div x-data="{
    show: false,
    message: '',
    type: 'success',
    init() {
        window.addEventListener('notify', (event) => {
            this.message = event.detail.message;
            this.type = event.detail.type || 'success';
            this.show = true;
            setTimeout(() => { this.show = false; }, 5000);
        });
    }
}" x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg overflow-hidden"
     :class="{
        'bg-green-50 border border-green-200': type === 'success',
        'bg-red-50 border border-red-200': type === 'error',
        'bg-blue-50 border border-blue-200': type === 'info',
        'bg-yellow-50 border border-yellow-200': type === 'warning'
     }"
     style="display: none;">
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg x-show="type === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg x-show="type === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <svg x-show="type === 'info'" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg x-show="type === 'warning'" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p x-text="message" class="text-sm font-medium text-gray-900"></p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('alpine:init', () => {
        window.dispatchEvent(new CustomEvent('notify', {
            detail: {
                message: '{{ session('success') }}',
                type: 'success'
            }
        }));
    });
</script>
@endif

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Perfil de Estudiante</h1>
        <div class="flex space-x-2 mt-4 sm:mt-0">
            <a href="{{ route('mentor.students') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver a la lista
            </a>
            <a href="{{ route('mentor.sessions.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Programar Sesión
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna de información del perfil -->
        <div class="lg:col-span-1">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-800">
                    <h3 class="text-lg leading-6 font-medium text-white">Información del Estudiante</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="text-center">
                        <div class="mx-auto h-40 w-40 relative">
                            @if($student->profile && $student->profile->avatar)
                                <img src="{{ Storage::url($student->profile->avatar) }}" 
                                     alt="{{ $student->name }}" 
                                     class="h-40 w-40 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="h-40 w-40 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-4xl font-bold border-4 border-white shadow-lg">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <!-- Online Status Indicator -->
                            <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-full ring-2 ring-white 
                                      {{ $student->isOnline() ? 'bg-green-500' : 'bg-gray-400' }}" 
                                  data-tippy-content="{{ $student->isOnline() ? 'En línea' : 'Fuera de línea' }}">
                            </span>
                        </div>
                        
                        <h3 class="mt-4 text-xl font-bold text-gray-900">{{ $student->name }}</h3>
                        <p class="text-sm text-gray-500">Estudiante desde {{ $student->created_at->format('d M, Y') }}</p>
                        
                        <!-- Social Links -->
                        <div class="mt-4 flex justify-center space-x-3">
                            <a href="mailto:{{ $student->email }}" 
                               class="inline-flex items-center p-2 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors duration-200"
                               data-tippy-content="Enviar Email">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </a>
                            
                            @if($student->profile && $student->profile->linkedin_url)
                            <a href="{{ $student->profile->linkedin_url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="inline-flex items-center p-2 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors duration-200"
                               data-tippy-content="Ver perfil de LinkedIn">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            @endif
                            
                            @if($student->profile && $student->profile->github_url)
                            <a href="{{ $student->profile->github_url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="inline-flex items-center p-2 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200"
                               data-tippy-content="Ver perfil de GitHub">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <dl class="space-y-4">
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->email }}</dd>
                            </div>
                            @if($student->profile)
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->profile->phone ?? 'No especificado' }}</dd>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $student->profile->location ?? 'No especificada' }}
                                </dd>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Idiomas</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $student->profile->languages ? implode(', ', json_decode($student->profile->languages, true)) : 'No especificados' }}
                                </dd>
                            </div>
                            @endif
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Última Actividad</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $student->last_activity_at ? $student->last_activity_at->format('d M, Y H:i') : 'Sin actividad registrada' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Columna de cursos y sesiones -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Cursos inscritos -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-white">Cursos Inscritos</h3>
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $student->enrollments->count() }} {{ Str::plural('curso', $student->enrollments->count()) }}
                        </span>
                    </div>
                </div>
                <div class="bg-white px-4 py-5 sm:p-6">
                    @if($student->enrollments->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200">
                                @foreach($student->enrollments as $enrollment)
                                <li class="py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($enrollment->course->image)
                                                <img class="h-16 w-16 rounded-md object-cover" 
                                                     src="{{ Storage::url($enrollment->course->image) }}" 
                                                     alt="{{ $enrollment->course->title }}">
                                            @else
                                                <div class="h-16 w-16 rounded-md bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                                                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <a href="{{ route('mentor.courses.show', $enrollment->course->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-900 hover:underline truncate">
                                                    {{ $enrollment->course->title }}
                                                </a>
                                                <div class="ml-2 flex-shrink-0 flex
                                                    {{ $enrollment->completed_at ? 'text-green-600' : 'text-yellow-600' }}"
                                                    data-tippy-content="{{ $enrollment->completed_at ? 'Completado el ' . $enrollment->completed_at->format('d M, Y') : 'En progreso' }}">
                                                    @if($enrollment->completed_at)
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-500">
                                                        Inscrito el {{ $enrollment->created_at->format('d M, Y') }}
                                                    </span>
                                                    <span class="text-xs font-medium text-gray-900">
                                                        {{ $enrollment->progress }}% completado
                                                    </span>
                                                </div>
                                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('mentor.courses.show', $enrollment->course->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                               data-tippy-content="Ver detalles del curso">
                                                Ver curso
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin cursos inscritos</h3>
                            <p class="mt-1 text-sm text-gray-500">Este estudiante no está inscrito en ningún curso actualmente.</p>
                            <div class="mt-6">
                                <a href="{{ route('mentor.courses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12 2a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H5a1 1 0 110-2h5V3a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Ver todos los cursos
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Sesiones de mentoría -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-white">Sesiones de Mentoría</h3>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $sessions->count() }} {{ Str::plural('sesión', $sessions->count()) }}
                            </span>
                            <a href="{{ route('mentor.sessions.create', ['mentee_id' => $student->id]) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Nueva Sesión
                            </a>
                        </div>
                    </div>
                </div>
                <div class="bg-white px-4 py-5 sm:p-6">
                    @if($sessions->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200">
                                @foreach($sessions as $session)
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-purple-100 text-purple-800',
                                    ];
                                    $statusIcons = [
                                        'scheduled' => 'calendar',
                                        'completed' => 'check-circle',
                                        'cancelled' => 'x-circle',
                                        'pending' => 'clock',
                                        'in_progress' => 'refresh',
                                    ];
                                    $statusTexts = [
                                        'scheduled' => 'Programada',
                                        'completed' => 'Completada',
                                        'cancelled' => 'Cancelada',
                                        'pending' => 'Pendiente',
                                        'in_progress' => 'En progreso',
                                    ];
                                    $statusColor = $statusColors[$session->status] ?? 'bg-gray-100 text-gray-800';
                                    $statusIcon = $statusIcons[$session->status] ?? 'question-mark-circle';
                                    $statusText = $statusTexts[$session->status] ?? ucfirst($session->status);
                                @endphp
                                <li class="py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center {{ $statusColor }}">
                                                    @switch($statusIcon)
                                                        @case('calendar')
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            @break
                                                        @case('check-circle')
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            @break
                                                        @case('x-circle')
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            @break
                                                        @case('clock')
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            @break
                                                        @case('refresh')
                                                            <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                            </svg>
                                                            @break
                                                        @default
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                    @endswitch
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $session->title }}
                                                        </p>
                                                        <div class="flex items-center mt-1">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                                {{ $statusText }}
                                                            </span>
                                                            <span class="ml-2 text-xs text-gray-500">
                                                                {{ $session->scheduled_at->isoFormat('D MMM, YYYY - hh:mm A') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('mentor.sessions.show', $session->id) }}" 
                                                   class="inline-flex items-center p-1.5 border border-transparent rounded-full text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                   data-tippy-content="Ver detalles">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @if(in_array($session->status, ['scheduled', 'pending']))
                                                <a href="{{ route('mentor.sessions.edit', $session->id) }}" 
                                                   class="inline-flex items-center p-1.5 border border-transparent rounded-full text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                                                   data-tippy-content="Editar sesión">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                @endif
                                                @if($session->meeting_url && in_array($session->status, ['scheduled', 'in_progress']))
                                                <a href="{{ $session->meeting_url }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center p-1.5 border border-transparent rounded-full text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                   data-tippy-content="Unirse a la sesión">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay sesiones programadas</h3>
                            <p class="mt-1 text-sm text-gray-500">Empieza programando una nueva sesión de mentoría.</p>
                            <div class="mt-6">
                                <a href="{{ route('mentor.sessions.create', ['mentee_id' => $student->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Programar sesión
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Initialize tooltips
        tippy('[data-tippy-content]', {
            arrow: true,
            animation: 'scale',
            duration: 200,
            theme: 'light-border',
            placement: 'top',
        });
    });
</script>
@endpush

@endsection
