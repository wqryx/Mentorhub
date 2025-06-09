@extends('mentor.layouts.app')

@section('title', 'Dashboard de Mentor - MentorHub')

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

    <!-- Content Row - Calendario Mejorado -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-blue-600">Calendario de Sesiones</h3>
                <span class="text-sm text-gray-500">Haz clic en un evento para ver detalles</span>
            </div>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <div id="mentorCalendar" class="h-96 rounded-lg border border-blue-100 shadow-sm"></div>
                </div>
                <div>
                    <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center"><i class="fas fa-list-ul mr-2 text-blue-400"></i>Próximas Sesiones</h4>
                    @if(isset($calendarEvents) && count($calendarEvents) > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($calendarEvents as $event)
                                <li class="py-3 flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-gray-800">
                                            <i class="fas fa-user-graduate text-blue-400 mr-1"></i>
                                            {{ $event['mentee'] ?? '-' }}
                                            <span class="ml-2 text-xs text-gray-500">{{ $event['course'] ?? '' }}</span>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-0.5">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($event['start'])->translatedFormat('D M Y, H:i') }}
                                            <span class="ml-2"><i class="fas fa-clock mr-1"></i>{{ $event['duration'] ?? '—' }} min</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-0.5 rounded text-xs {{ $event['status'] === 'completed' ? 'bg-green-100 text-green-700' : ($event['status'] === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ ucfirst($event['status'] ?? '-') }}
                                        </span>
                                        @if(isset($event['url']))
                                            <a href="{{ $event['url'] }}" class="text-blue-500 hover:text-blue-700 text-sm ml-2" title="Ver Detalles"><i class="fas fa-eye"></i></a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-gray-400 text-center py-8">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <div class="text-sm">No hay sesiones programadas</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Rechazar Solicitud -->

@endsection

@push('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('css/mentor-dashboard.css') }}">
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

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

    // Inicializar el calendario
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
            events: @json($calendarEvents ?? []),
            eventClick: function(info) {
                // Mostrar modal con detalles de la sesión
                const event = info.event.extendedProps;
                let details = `<div class='text-left'>` +
                    `<div class='font-bold mb-1 text-blue-700'><i class='fas fa-user-graduate mr-1'></i>Alumno: ${event.mentee ?? '-'} </div>` +
                    `<div class='mb-1'><i class='fas fa-book mr-1'></i>Curso: ${event.course ?? '-'} </div>` +
                    `<div class='mb-1'><i class='fas fa-clock mr-1'></i>Duración: ${event.duration ?? '-'} min</div>` +
                    `<div class='mb-1'><i class='fas fa-info-circle mr-1'></i>Estado: ${event.status ?? '-'}</div>` +
                    (info.event.url ? `<a href='${info.event.url}' class='text-blue-600 underline' target='_blank'>Ver detalles</a>` : '') +
                    `</div>`;
                if (window.Swal) {
                    Swal.fire({
                        title: info.event.title,
                        html: details,
                        icon: 'info',
                        confirmButtonText: 'Cerrar',
                        customClass: {popup: 'rounded-lg'}
                    });
                } else {
                    alert(info.event.title + "\n" + (event.mentee ? 'Alumno: ' + event.mentee : ''));
                }
                return false;
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }
        });
        calendar.render();
    }
});
</script>
@endpush
