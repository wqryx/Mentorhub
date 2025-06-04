@extends('mentor.layouts.app')

@section('title', 'Perfil de Estudiante - ' . $student->name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- Add any specific styles for this page if needed --}}
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Perfil de {{ $student->name }}
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
            <a href="{{ route('mentor.sessions.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i> Programar Sesión
            </a>
            <a href="{{ route('mentor.students') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Estudiantes
            </a>
        </div>
    </div>

    @include('partials.alerts') {{-- Assuming this partial is styled for Tailwind or generic --}}

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <!-- Columna Izquierda: Información del Estudiante -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Información del Estudiante
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="mx-auto mb-4">
                        @if($student->profile && $student->profile->avatar)
                            <img class="h-24 w-24 rounded-full mx-auto object-cover" src="{{ Storage::url($student->profile->avatar) }}" alt="{{ $student->name }}">
                        @else
                            <div class="h-24 w-24 rounded-full bg-blue-500 flex items-center justify-center text-white text-3xl font-semibold mx-auto">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900">{{ $student->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $student->email }}</p>
                    <p class="text-xs text-gray-400 mt-1">Estudiante desde {{ $student->created_at->format('M Y') }}</p>

                    <div class="mt-4 flex justify-center space-x-3">
                        <a href="mailto:{{ $student->email }}" class="text-gray-400 hover:text-blue-500 p-1" data-tippy-content="Enviar Email">
                            <i class="fas fa-envelope fa-lg"></i>
                        </a>
                        @if($student->profile && $student->profile->linkedin_url)
                        <a href="{{ $student->profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-500 p-1" data-tippy-content="Perfil de LinkedIn">
                            <i class="fab fa-linkedin-in fa-lg"></i>
                        </a>
                        @endif
                        @if($student->profile && $student->profile->github_url)
                        <a href="{{ $student->profile->github_url }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-gray-600 p-1" data-tippy-content="Perfil de GitHub">
                            <i class="fab fa-github fa-lg"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <h4 class="text-md font-medium text-gray-700 mb-3">Detalles Adicionales</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex justify-between"><span><strong>Correo:</strong></span> <span>{{ $student->email }}</span></li>
                        @if($student->profile)
                        <li class="flex justify-between"><span><strong>Teléfono:</strong></span> <span>{{ $student->profile->phone ?? 'No especificado' }}</span></li>
                        <li class="flex justify-between"><span><strong>Ubicación:</strong></span> <span>{{ $student->profile->location ?? 'No especificada' }}</span></li>
                        <li class="flex justify-between"><span><strong>Idiomas:</strong></span> <span>{{ $student->profile->languages ? implode(', ', json_decode($student->profile->languages, true)) : 'No especificados' }}</span></li>
                        @endif
                        <li class="flex justify-between"><span><strong>Última Actividad:</strong></span> <span>{{ $student->last_activity_at ? $student->last_activity_at->diffForHumans() : 'Nunca' }}</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Cursos y Sesiones -->
        <div class="md:col-span-2 space-y-6">
            <!-- Cursos Inscritos -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Cursos Inscritos</h3>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $student->enrollments->count() }} {{ Str::plural('curso', $student->enrollments->count()) }}
                    </span>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if($student->enrollments->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($student->enrollments as $enrollment)
                            <li class="py-4 flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($enrollment->course->image)
                                        <img class="h-16 w-16 rounded-md object-cover" src="{{ Storage::url($enrollment->course->image) }}" alt="{{ $enrollment->course->title }}">
                                    @else
                                        <div class="h-16 w-16 rounded-md bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-book text-gray-500 fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('mentor.courses.show', $enrollment->course->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 truncate">
                                        {{ $enrollment->course->title }}
                                    </a>
                                    <p class="text-xs text-gray-500">Inscrito: {{ $enrollment->created_at->format('d M, Y') }}</p>
                                    <div class="mt-1">
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $enrollment->progress }}% completado 
                                            @if($enrollment->completed_at)
                                                <span class="text-green-600 ml-1" data-tippy-content="Completado el {{ $enrollment->completed_at->format('d M, Y') }}"><i class="fas fa-check-circle"></i></span>
                                            @else
                                                <span class="text-yellow-600 ml-1" data-tippy-content="En progreso"><i class="fas fa-clock"></i></span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                {{-- Botón 'Ver' eliminado según solicitud --}}
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-book-reader fa-3x text-gray-400 mb-3"></i>
                            <h4 class="text-sm font-medium text-gray-900">Sin cursos inscritos</h4>
                            <p class="mt-1 text-sm text-gray-500">Este estudiante no está inscrito en ningún curso.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sesiones de Mentoría -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Sesiones de Mentoría</h3>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $sessions->total() }} {{ Str::plural('sesión', $sessions->total()) }}
                        </span>
                        <a href="{{ route('mentor.sessions.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-1"></i> Nueva
                        </a>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if($sessions->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($sessions as $session)
                            @php
                                $statusConfig = match ($session->status) {
                                    'scheduled' => ['text' => 'Programada', 'icon' => 'fa-calendar-alt', 'color' => 'blue'],
                                    'completed' => ['text' => 'Completada', 'icon' => 'fa-check-circle', 'color' => 'green'],
                                    'cancelled' => ['text' => 'Cancelada', 'icon' => 'fa-times-circle', 'color' => 'red'],
                                    'pending' => ['text' => 'Pendiente', 'icon' => 'fa-clock', 'color' => 'yellow'],
                                    'in_progress' => ['text' => 'En Progreso', 'icon' => 'fa-spinner fa-spin', 'color' => 'indigo'],
                                    default => ['text' => ucfirst($session->status), 'icon' => 'fa-question-circle', 'color' => 'gray'],
                                };
                            @endphp
                            <li class="py-4 flex items-center justify-between space-x-4">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <span class="text-{{ $statusConfig['color'] }}-500" style="font-size: 1.25rem;"><i class="fas {{ $statusConfig['icon'] }}"></i></span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $session->title }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $session->scheduled_at->isoFormat('D MMM, YYYY - hh:mm A') }}</p>
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $statusConfig['color'] }}-100 text-{{ $statusConfig['color'] }}-800">
                                        {{ $statusConfig['text'] }}
                                    </span>
                                </div>
                                <div class="flex-shrink-0 flex space-x-1">
                                    <a href="{{ route('mentor.sessions.show', $session->id) }}" class="p-1 text-gray-400 hover:text-blue-600" data-tippy-content="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(in_array($session->status, ['scheduled', 'pending']))
                                    <a href="{{ route('mentor.sessions.edit', $session->id) }}" class="p-1 text-gray-400 hover:text-yellow-600" data-tippy-content="Editar Sesión">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if($session->meeting_url && in_array($session->status, ['scheduled', 'in_progress']))
                                    <a href="{{ $session->meeting_url }}" target="_blank" class="p-1 text-gray-400 hover:text-green-600" data-tippy-content="Unirse a la Sesión">
                                        <i class="fas fa-video"></i>
                                    </a>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @if ($sessions->hasPages())
                            <div class="mt-4">
                                {{ $sessions->links() }} {{-- Ensure pagination is styled for Tailwind --}}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times fa-3x text-gray-400 mb-3"></i>
                            <h4 class="text-sm font-medium text-gray-900">No hay sesiones programadas</h4>
                            <p class="mt-1 text-sm text-gray-500">Este estudiante aún no tiene sesiones de mentoría.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script>
    document.addEventListener('alpine:init', () => {
        // Initialize Tippy.js tooltips
        if (typeof tippy !== 'undefined') {
            tippy('[data-tippy-content]', {
                placement: 'top',
                animation: 'scale-subtle',
                arrow: true,
                theme: 'light-border',
            });
        }
    });

    // Configuración de Flatpickr (si es necesario aquí o ya está en otro lado)
    if (typeof flatpickr !== 'undefined') {
        flatpickr(".flatpickr", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            locale: "es",
            time_24hr: true
        });
    }
</script>
@endpush
