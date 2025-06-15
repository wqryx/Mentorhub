@extends('mentor.layouts.app')

@section('title', 'Dashboard de Mentor - MentorHub')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
<style>
    /* Estilos personalizados para el calendario */
    #mentorCalendar .fc {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    #mentorCalendar .fc-header-toolbar {
        margin-bottom: 1em;
        padding: 0 1em;
        padding-top: 1em;
    }
    #mentorCalendar .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
    }
    #mentorCalendar .fc-button {
        background-color: #3b82f6;
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
    }
    #mentorCalendar .fc-button:hover {
        background-color: #2563eb;
    }
    #mentorCalendar .fc-button:active {
        background-color: #1d4ed8;
    }
    #mentorCalendar .fc-day-today {
        background-color: #eff6ff !important;
    }
    #mentorCalendar .fc-event {
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        border: none;
    }
    #mentorCalendar .fc-event:hover {
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto">
    <!-- Page Heading -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Panel de Control</h1>
        <a href="{{ route('mentor.mentorias.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition-colors duration-150">
            <i class="fas fa-plus-circle mr-2"></i> Nueva Sesión
        </a>
    </div>

    <!-- Content Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Gestionar Mis Cursos Card -->
        <a href="{{ route('mentor.courses.index') }}" class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500 hover:bg-purple-50 transition-colors duration-150 block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-purple-600 uppercase mb-1">
                        Formación
                    </p>
                    <p class="text-xl font-bold text-gray-800">Gestionar Mis Cursos</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-chalkboard-teacher text-xl text-purple-500"></i>
                </div>
            </div>
        </a>
        <!-- Total de Sesiones Card -->
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">
                        Total de Sesiones
                    </p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_sessions'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-calendar text-xl text-blue-500"></i>
                </div>
            </div>
        </div>

        <!-- Próximas Sesiones Card -->
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-green-600 uppercase mb-1">
                        Próximas Sesiones
                    </p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['upcoming_sessions'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-clock text-xl text-green-500"></i>
                </div>
            </div>
        </div>

        <!-- Solicitudes Pendientes Card -->
        <!-- Solicitudes Pendientes Card -->
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-yellow-600 uppercase mb-1">
                        Solicitudes Pendientes
                    </p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_requests'] ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-comments text-xl text-yellow-500"></i>
                </div>
            </div>
        </div>

        <!-- Tasa de Finalización Card -->
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-cyan-500">
            <div class="flex items-center justify-between">
                <div class="w-full">
                    <p class="text-xs font-bold text-cyan-600 uppercase mb-1">
                        Tasa de Finalización
                    </p>
                    <div class="flex items-center mb-2">
                        <p class="text-2xl font-bold text-gray-800 mr-3">{{ number_format($stats['completion_rate'] ?? 0, 1) }}%</p>
                        <div class="bg-cyan-100 p-2 rounded-full">
                            <i class="fas fa-clipboard-list text-cyan-500"></i>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-cyan-500 h-2.5 rounded-full" style="width: {{ $stats['completion_rate'] ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario de Eventos -->
    <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-blue-600 flex items-center">
                <i class="far fa-calendar-alt mr-2"></i>
                Mi Calendario de Actividades
            </h3>
            <span class="text-sm text-gray-500">Haz clic en un evento para ver detalles</span>
        </div>
        <div class="p-6">
            <div id="mentorCalendar" class="h-96"></div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Próximas Sesiones -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-blue-600">Próximas Sesiones</h3>
                <a href="{{ route('mentor.mentorias.index') }}" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors duration-150">
                    Ver Todas
                </a>
            </div>
            <div class="p-6">
                @if(isset($upcomingSessions) && $upcomingSessions->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($upcomingSessions as $session)
                            <a href="{{ route('mentor.mentorias.show', $session->id) }}" 
                               class="block p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-medium text-gray-800">{{ $session->title }}</h4>
                                    <span class="text-xs text-gray-500">{{ $session->start_time->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user-graduate mr-1"></i>{{ $session->mentee->name }}
                                    @if($session->course)
                                        <span class="ml-3"><i class="fas fa-book mr-1"></i>{{ $session->course->title }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $session->start_time->format('l, d M Y - H:i') }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No tienes sesiones programadas.</p>
                @endif
            </div>
        </div>

        <!-- Solicitudes Recientes -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-yellow-600">Solicitudes Recientes</h3>
                <a href="{{ route('mentor.mentorias.index') }}" class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition-colors duration-150">
                    Ver Todas
                </a>
            </div>
            <div class="p-6">
                @if(isset($pendingRequests) && $pendingRequests->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($pendingRequests as $request)
                            <div class="border border-gray-100 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-medium text-gray-800">{{ $request->mentee->name }}</h4>
                                    <span class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $request->message ? Str::limit($request->message, 80) : 'Sin mensaje' }}</p>
                                <div class="flex space-x-2 mt-3">
                                    <form action="{{ route('mentor.mentorias.respond', $request->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition-colors duration-150">
                                            <i class="fas fa-check mr-1"></i> Aceptar
                                        </button>
                                    </form>
                                    <button type="button" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition-colors duration-150" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectRequestModal"
                                            data-request-id="{{ $request->id }}">
                                        <i class="fas fa-times mr-1"></i> Rechazar
                                    </button>
                                    <a href="{{ route('mentor.mentorias.show', $request->id) }}" 
                                       class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors duration-150">
                                        <i class="fas fa-eye mr-1"></i> Ver
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No tienes solicitudes pendientes.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para Rechazar Solicitud -->

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('css/mentor-dashboard.css') }}">
@endpush

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar el modal de rechazo
    const rejectModal = document.getElementById('rejectRequestModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const requestId = button.getAttribute('data-request-id');
            const form = document.getElementById('rejectRequestForm');
            // Construir la URL correctamente
            form.action = `/mentor/sessions/${requestId}/respond`;
        });
    }
    
    // Inicializar FullCalendar si existe el elemento
    const calendarEl = document.getElementById('mentorCalendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
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
                day: 'Día',
                list: 'Lista'
            },
            events: @json($formattedEvents ?? []),
            eventClick: function(info) {
                // Abrir el enlace del evento al hacer clic
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.open(info.event.url, '_self');
                }
            },
            height: 'auto',
            navLinks: true,
            nowIndicator: true,
            editable: false,
            selectable: false,
            selectMirror: true,
            dayMaxEvents: true,
            firstDay: 1, // Lunes como primer día de la semana
            businessHours: {
                // Horario laboral de lunes a viernes de 9:00 a 20:00
                daysOfWeek: [1, 2, 3, 4, 5],
                startTime: '09:00',
                endTime: '20:00',
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            views: {
                timeGrid: {
                    dayMaxEventRows: 6 // Ajustar según sea necesario
                }
            }
        });
        
        calendar.render();
        
        // Ajustar el tamaño del calendario cuando cambia el tamaño de la ventana
        window.addEventListener('resize', function() {
            calendar.updateSize();
        });
    }
});
</script>
@endpush
