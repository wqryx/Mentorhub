@extends('mentor.layouts.app')

@section('title', 'Mis Sesiones - MentorHub')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mis Sesiones de Mentoría</h1>
        <a href="{{ route('mentor.sessions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-plus-circle mr-2"></i> Nueva Sesión
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Pestañas -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <a href="#upcoming" @click="activeTab = 'upcoming'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'upcoming', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'upcoming' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Próximas
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                        {{ $upcomingSessions->count() }}
                    </span>
                </a>
                <a href="#pending" @click="activeTab = 'pending'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'pending', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pending' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Pendientes
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">
                        {{ $pendingRequests->count() }}
                    </span>
                </a>
                <a href="#past" @click="activeTab = 'past'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'past', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'past' }" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Historial
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ml-1">
                        {{ $pastSessions->total() }}
                    </span>
                </a>
            </nav>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="p-6">
            <!-- Pestaña de Próximas Sesiones -->
            <div x-show="activeTab === 'upcoming'" x-transition>
                @if($upcomingSessions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha y Hora
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duración
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Curso
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($upcomingSessions as $session)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $session->mentee->profile_photo_url }}" alt="{{ $session->mentee->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $session->mentee->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $session->mentee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($session->start_time)->format('d M Y, H:i') }}</div>
                                        <div class="text-sm text-gray-500">Finaliza: {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $session->duration }} minutos
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $session->course->title ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $session->meeting_platform }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($session->status === 'scheduled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Programada
                                            </span>
                                        @elseif($session->status === 'in_progress')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                En curso
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst(str_replace('_', ' ', $session->status)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2">
                                            @if($session->status === 'scheduled' && $session->start_time <= now()->addMinutes(15) && $session->start_time >= now())
                                                <button 
                                                    @click="openStartModal(
                                                        {{ $session->id }}, 
                                                        '{{ addslashes($session->mentee->name) }}',
                                                        '{{ $session->meeting_url ?? '' }}'
                                                    )" 
                                                    class="text-green-600 hover:text-green-900"
                                                    title="Iniciar sesión"
                                                >
                                                    <i class="fas fa-video"></i>
                                                </button>
                                            @endif
                                            <a 
                                                href="{{ route('mentor.sessions.show', $session->id) }}" 
                                                class="text-blue-600 hover:text-blue-900"
                                                title="Ver detalles"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('mentor.sessions.edit', $session->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button @click="openModal('deleteModal', { id: {{ $session->id }} })" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay sesiones programadas</h3>
                        <p class="mt-1 text-sm text-gray-500">No tienes sesiones programadas en este momento.</p>
                        <div class="mt-6">
                            <a href="{{ route('mentor.sessions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus-circle -ml-1 mr-2 h-5 w-5"></i>
                                Nueva Sesión
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Pestaña de Solicitudes Pendientes -->
            <div x-show="activeTab === 'pending'" x-transition>
                @if($pendingRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Solicitado el
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha Propuesta
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Curso
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mensaje
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingRequests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $request->mentee->profile_photo_url }}" alt="{{ $request->mentee->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->mentee->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $request->mentee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $request->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($request->proposed_time)->format('d M Y, H:i') }}</div>
                                        <div class="text-sm text-gray-500">Duración: {{ $request->duration }} min</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $request->course->title ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->meeting_platform }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="line-clamp-2">{{ $request->message ?? 'Sin mensaje' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2 justify-end">
                                            <button @click="openModal('acceptRequestModal', { id: {{ $request->id }} })" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button @click="openModal('rejectRequestModal', { id: {{ $request->id }} })" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <a href="{{ route('mentor.sessions.show-request', $request->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay solicitudes pendientes</h3>
                        <p class="mt-1 text-sm text-gray-500">No tienes solicitudes de sesión pendientes en este momento.</p>
                    </div>
                @endif
            </div>

            <!-- Pestaña de Historial -->
            <div x-show="activeTab === 'past'" x-transition>
                @if($pastSessions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha y Hora
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duración
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Curso
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pastSessions as $session)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="{{ $session->mentee->profile_photo_url }}" alt="{{ $session->mentee->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $session->mentee->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $session->mentee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($session->start_time)->format('d M Y, H:i') }}</div>
                                        <div class="text-sm text-gray-500">Finalizó: {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $session->duration }} minutos
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $session->course->title ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $session->meeting_platform }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($session->status === 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completada
                                            </span>
                                        @elseif($session->status === 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelada
                                            </span>
                                        @elseif($session->status === 'no_show')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                No asistió
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst(str_replace('_', ' ', $session->status)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2 justify-end">
                                            <a href="{{ route('mentor.sessions.show', $session->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$session->reviewed_by_mentor)
                                                <button @click="openModal('reviewSessionModal', { id: {{ $session->id }} })" class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $pastSessions->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay historial de sesiones</h3>
                        <p class="mt-1 text-sm text-gray-500">Aún no has tenido sesiones de mentoría.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos para el sistema de valoración por estrellas */
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating input {
        display: none;
    }
    .rating label {
        color: #d4d4d4;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0 2px;
    }
    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
        color: #f59e0b;
    }
    .rating input:checked ~ label:hover,
    .rating input:checked ~ label:hover ~ label,
    .rating input:checked ~ label:hover ~ label ~ label,
    .rating input:checked ~ label:hover ~ label ~ label ~ label,
    .rating input:checked ~ label:hover ~ label ~ label ~ label ~ label {
        color: #f59e0b;
    }
    .rating input:checked + label,
    .rating input:checked ~ label {
        color: #f59e0b;
    }
</style>
@endpush

</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mentorSessions', () => ({
            // Estado de las pestañas
            activeTab: 'upcoming',
            
            // Estado del modal de inicio de sesión
            // Eliminado temporalmente
            
            // Estado del modal de valoración
            showReviewModal: false,
            reviewData: {
                id: null,
                studentName: '',
                rating: 0,
                comment: ''
            },
            
            // Estado del modal de aceptar solicitud
            showAcceptRequestModal: false,
            acceptRequestData: {
                id: null,
                studentName: '',
                message: ''
            },
            
            // Estado del modal de rechazar solicitud
            showRejectRequestModal: false,
            rejectRequestData: {
                id: null,
                studentName: '',
                reason: ''
            },
            
            // Inicialización
            init() {
                // Verificar si hay un hash en la URL para activar la pestaña correspondiente
                const hash = window.location.hash.substring(1);
                if (['upcoming', 'pending', 'past'].includes(hash)) {
                    this.activeTab = hash;
                }
            },
            
            // Métodos de inicio de sesión eliminados temporalmente
            
            // Método para abrir el modal de valoración
            openReviewModal(sessionId, studentName) {
                this.reviewData = {
                    id: sessionId,
                    studentName: studentName,
                    rating: 0,
                    comment: ''
                };
                this.showReviewModal = true;
            },
            
            // Método para establecer la calificación
            setRating(rating) {
                this.reviewData.rating = rating;
            },
            
            // Método para abrir el modal de aceptar solicitud
            openAcceptRequestModal(requestId, studentName) {
                this.acceptRequestData = {
                    id: requestId,
                    studentName: studentName,
                    message: '¡Hola! Estoy encantado de poder ayudarte con tu solicitud de mentoría.'
                };
                this.showAcceptRequestModal = true;
            },
            
            // Método para abrir el modal de rechazar solicitud
            openRejectRequestModal(requestId, studentName) {
                this.rejectRequestData = {
                    id: requestId,
                    studentName: studentName,
                    reason: ''
                };
                this.showRejectRequestModal = true;
            }
        }));
    });
</script>
@endpush
