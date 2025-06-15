@extends('student.layouts.app')

@section('title', 'Dashboard de Estudiante - MentorHub')

@section('content')
<div class="container mx-auto">
    <!-- Page Heading -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Panel de Estudiante</h1>
    </div>

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg text-white p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold">¡Bienvenido de nuevo, {{ Auth::user()->name }}!</h2>
                <p class="mt-2 opacity-90">Aquí está un resumen de tu progreso y actividades recientes.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-block bg-white/20 px-4 py-2 rounded-full text-sm font-medium">
                    {{ now()->format('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Cursos en Progreso -->
        <a href="{{ route('student.courses') }}" class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1">
                        Cursos
                    </p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_courses'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Mis Cursos</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-book-open text-xl text-purple-500"></i>
                </div>
            </div>
        </a>

<!-- Progreso General Card -->
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-xs font-bold text-green-600 uppercase">
                            Progreso
                        </p>
                    </div>
                    <div class="bg-green-100 p-2 rounded-full">
                        <i class="fas fa-chart-line text-green-500"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['average_progress'] ?? 0, 1) }}%</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['average_progress'] ?? 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Promedio de finalización</p>
                </div>
            </div>
        </div>

        <!-- Próximos Eventos Card -->
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-yellow-600 uppercase mb-1">
                        Eventos
                    </p>
                    <p class="text-2xl font-bold text-gray-800">{{ $upcomingEvents->count() ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Próximos</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="far fa-calendar-alt text-xl text-yellow-500"></i>
                </div>
            </div>
        </div>
    </div>
                    
    <!-- Cursos en Progreso -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-book-reader text-purple-500 mr-2"></i>
                Mis Cursos en Progreso
            </h3>
            <a href="{{ route('student.courses') }}" class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 text-sm font-medium rounded-md hover:bg-purple-200 transition-colors duration-150">
                Ver Todos <i class="fas fa-chevron-right ml-1 text-xs"></i>
            </a>
        </div>
        <div class="p-6">
            @if($inProgressCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($inProgressCourses as $enrollment)
                        @php $course = $enrollment->course; @endphp
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-300">
                            <div class="h-40 bg-gray-100 relative">
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-center">
                                        <span class="text-white text-4xl font-bold">{{ substr($course->title, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-white text-sm font-medium">{{ $course->category->name ?? 'Sin categoría' }}</span>
                                        <span class="px-2 py-1 bg-white/20 backdrop-blur-sm text-white text-xs rounded-full">{{ $course->level }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2" title="{{ $course->title }}">{{ $course->title }}</h4>
                                <div class="flex items-center text-sm text-gray-600 mb-4">
                                    <img src="{{ $course->instructor->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="{{ $course->instructor->name }}" 
                                         class="w-6 h-6 rounded-full mr-2">
                                    <span>{{ $course->instructor->name }}</span>
                                </div>
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span>Progreso</span>
                                        @php $progress = $course->progress(Auth::id()) ?? 0; @endphp
                                        <span>{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-purple-500 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="far fa-clock mr-1.5"></i>
                                        <span>{{ $course->duration }}h</span>
                                    </div>
                                    <a href="{{ route('student.courses.show', $course->id) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-50 text-purple-700 text-sm font-medium rounded-md hover:bg-purple-100 transition-colors duration-150">
                                        Continuar <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 px-4">
                    <div class="mx-auto w-16 h-16 flex items-center justify-center bg-purple-100 rounded-full mb-4">
                        <i class="fas fa-book-open text-2xl text-purple-500"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No hay cursos en progreso</h4>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Explora nuestros cursos disponibles y comienza tu viaje de aprendizaje hoy mismo.</p>
                    <a href="{{ route('student.courses.available') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <i class="fas fa-search mr-2"></i> Explorar Cursos
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Calendario de Eventos -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-blue-600 flex items-center">
                <i class="far fa-calendar-alt mr-2"></i>
                Mi Calendario de Actividades
            </h3>
            <span class="text-sm text-gray-500">Haz clic en un evento para ver detalles</span>
        </div>
        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <div id="studentCalendar" class="h-96 rounded-lg border border-blue-100 shadow-sm"></div>
            </div>
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-list-ul mr-2 text-blue-400"></i>
                    Próximos Eventos
                </h4>
                @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($upcomingEvents->take(5) as $event)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-800">
                                        <i class="fas fa-chalkboard-teacher text-blue-400 mr-1"></i>
                                        {{ $event->title }}
                                        @if($event->course)
                                            <span class="ml-2 text-xs text-gray-500">{{ $event->course->title }}</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500 mt-0.5">
                                        <i class="far fa-calendar-alt mr-1"></i> 
                                        {{ $event->start_date->translatedFormat('D d M Y, H:i') }}
                                        @if($event->duration)
                                            <span class="ml-2"><i class="fas fa-clock mr-1"></i>{{ $event->duration }} min</span>
                                        @endif
                                    </div>
                                    @if($event->location)
                                        <div class="text-sm text-gray-500 mt-0.5">
                                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $event->type ?? 'Evento' }}
                                    </span>
                                    @if(isset($event->url))
                                        <a href="{{ $event->url }}" class="text-blue-500 hover:text-blue-700 text-sm" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-gray-400 text-center py-8">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <div class="text-sm">No hay eventos programados</div>
                    </div>
                @endif
                <div class="mt-4 text-center">
                    <a href="{{ route('student.calendar') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                        Ver calendario completo <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Actividad Reciente -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Actividad Reciente</h3>
        </div>
        <div class="p-6">
            @if(isset($recentActivities) && $recentActivities->isNotEmpty())
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($recentActivities as $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" 
                                              aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @php
                                                $iconClass = [
                                                    'completed' => 'bg-green-500',
                                                    'created' => 'bg-blue-500',
                                                    'updated' => 'bg-yellow-500',
                                                    'deleted' => 'bg-red-500',
                                                    'default' => 'bg-indigo-500'
                                                ][$activity->type] ?? 'bg-indigo-500';
                                                
                                                $icon = [
                                                    'completed' => 'fas fa-check-circle',
                                                    'created' => 'fas fa-plus-circle',
                                                    'updated' => 'fas fa-edit',
                                                    'deleted' => 'fas fa-trash-alt',
                                                    'default' => 'fas fa-bell'
                                                ][$activity->type] ?? 'fas fa-bell';
                                            @endphp
                                            <span class="h-8 w-8 rounded-full {{ $iconClass }} flex items-center justify-center ring-8 ring-white">
                                                <i class="{{ $icon }} text-white text-sm"></i>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-800">
                                                    <span class="font-medium text-gray-900">{{ $activity->user->name }}</span>
                                                    {{ $activity->description }}
                                                    @if($activity->subject_type && $activity->subject)
                                                        <a href="{{ $activity->getUrl() }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                                            {{ $activity->subject->name ?? $activity->subject->title }}
                                                        </a>
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
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-lg">
                    <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No hay actividad reciente para mostrar.</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <style>
        /* Estilos personalizados para el calendario */
        #studentCalendar .fc {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        #studentCalendar .fc-toolbar-title {
            font-size: 1.1rem;
            font-weight: 600;
        }
        #studentCalendar .fc-button {
            background-color: #3b82f6;
            border: none;
            padding: 0.35rem 0.7rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }
        #studentCalendar .fc-button:hover {
            background-color: #2563eb;
        }
        #studentCalendar .fc-button-active {
            background-color: #1d4ed8;
        }
        #studentCalendar .fc-event {
            cursor: pointer;
            border: none;
            font-size: 0.75rem;
            padding: 0.15rem 0.3rem;
            margin: 0.1rem;
            border-radius: 0.25rem;
        }
        #studentCalendar .fc-daygrid-event-dot {
            display: none;
        }
    </style>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js'></script>
    
    <script>
        // Inicializar el calendario cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('studentCalendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                events: [
                    @if(isset($calendarEvents) && count($calendarEvents) > 0)
                        @foreach($calendarEvents as $event)
                        {
                            title: '{{ addslashes($event['title']) }}',
                            start: '{{ $event['start'] }}',
                            @if(isset($event['end']))
                                end: '{{ $event['end'] }}',
                            @endif
                            @if(isset($event['url']))
                                url: '{{ $event['url'] }}',
                            @endif
                            backgroundColor: '{{ $event['backgroundColor'] ?? '#3b82f6' }}',
                            borderColor: '{{ $event['borderColor'] ?? '#3b82f6' }}',
                            textColor: 'white',
                            extendedProps: {
                                description: '{{ $event['description'] ?? '' }}',
                                location: '{{ $event['location'] ?? '' }}',
                                type: '{{ $event['type'] ?? 'Evento' }}'
                            }
                        },
                        @endforeach
                    @endif
                ],
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.open(info.event.url, '_self');
                    }
                },
                eventMouseEnter: function(info) {
                    info.el.style.opacity = '0.9';
                },
                eventMouseLeave: function(info) {
                    info.el.style.opacity = '1';
                },
                height: '100%',
                contentHeight: 'auto',
                dayMaxEvents: 3,
                firstDay: 1, // Lunes como primer día de la semana
                navLinks: true, // Permite navegar a día/semana al hacer clic
                nowIndicator: true, // Muestra un indicador de la hora actual
                eventTimeFormat: { // Formato de la hora del evento
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                },
                // Personalizar el aspecto de los días del mes
                dayCellClassNames: function(arg) {
                    if (arg.isToday) {
                        return ['bg-blue-50'];
                    }
                    return [];
                },
                // Personalizar el encabezado de los días
                dayHeaderClassNames: function(arg) {
                    return ['text-sm font-medium text-gray-700'];
                },
                // Personalizar el número del día
                dayCellContent: function(arg) {
                    return { html: '<div class="text-center p-1">' + arg.dayNumberText + '</div>' };
                }
            });

            calendar.render();
        });

        // Marcar tarea como completada
        document.querySelectorAll('input[type="checkbox"][data-task-id]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const taskId = this.dataset.taskId;
                const taskCard = this.closest('.border');
                const url = `/student/tasks/${taskId}/complete`;
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PATCH',
                        completed: this.checked
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Animación al marcar como completado
                        if (this.checked) {
                            taskCard.classList.add('opacity-50', 'bg-green-50');
                            setTimeout(() => {
                                taskCard.remove();
                                // Mostrar notificación de éxito
                                showNotification('Tarea completada', 'success');
                            }, 300);
                        }
                    } else {
                        this.checked = !this.checked; // Revertir el cambio
                        showNotification('Error al actualizar la tarea', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !this.checked; // Revertir el cambio
                    showNotification('Error al conectar con el servidor', 'error');
                });
            });
        });

        // Función para mostrar notificaciones
        function showNotification(message, type = 'success') {
            const toast = document.createElement('div');
            const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 z-50 transition-all duration-300 transform translate-x-0 opacity-100`;
            toast.innerHTML = `
                <i class="fas fa-${icon} text-white"></i>
                <span>${message}</span>
                <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(toast);
            
            // Auto-remove notification after 5 seconds
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
    @endpush
@endsection
