@extends('mentor.layouts.app')

@section('title', 'Detalles de la Sesión - MentorHub')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalles de la Sesión</h1>
        <div class="space-x-2">
            <a href="{{ route('mentor.sessions.edit', $session) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
            <a href="{{ route('mentor.sessions.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </div>

    @include('partials.alerts')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $session->title }}</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $session->status === 'completed' ? 'bg-green-100 text-green-800' : 
                          ($session->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Información General</h4>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <i class="far fa-calendar-alt mt-1 mr-2 text-gray-500"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Fecha y Hora</p>
                                        <p class="text-sm text-gray-900">{{ $session->start_time->isoFormat('dddd, D [de] MMMM [de] YYYY - h:mm A') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="far fa-clock mt-1 mr-2 text-gray-500"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Duración</p>
                                        <p class="text-sm text-gray-900">{{ $session->duration }} minutos</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-user-graduate mt-1 mr-2 text-gray-500"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Estudiante</p>
                                        <p class="text-sm text-gray-900">{{ $session->mentee->name }}</p>
                                    </div>
                                </div>
                                @if($session->course)
                                <div class="flex items-start">
                                    <i class="fas fa-book mt-1 mr-2 text-gray-500"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Curso</p>
                                        <p class="text-sm text-gray-900">{{ $session->course->name }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Enlaces de la Reunión</h4>
                            @if($session->meeting_url)
                                <p>
                                    <strong><i class="fas fa-video me-2"></i>Enlace de la Reunión:</strong><br>
                                    <a href="{{ $session->meeting_url }}" target="_blank" class="text-primary">
                                        {{ $session->meeting_url }}
                                    </a>
                                </p>
                                <a href="{{ $session->meeting_url }}" target="_blank" class="btn btn-primary mb-3">
                                    <i class="fas fa-video me-1"></i> Unirse a la Reunión
                                </a>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No se ha proporcionado un enlace de reunión para esta sesión.
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($session->description)
                        <div class="mb-4">
                        </div>
                    @endif

                    @if($session->status === 'scheduled')
                        <div class="mt-6 pt-6 border-t border-gray-200 flex flex-wrap gap-3">
                            <form action="{{ route('mentor.sessions.update-status', $session) }}" method="POST" class="inline-flex">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-check-circle mr-2"></i> Marcar como Completada
                                </button>
                            </form>

                            <button type="button" 
                                    @click="showCancelModal = true"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-times-circle mr-2"></i> Cancelar Sesión
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sección de Reseñas -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Reseñas</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if(empty($session->reviews) || $session->reviews->isEmpty())
                        <p class="text-gray-500 text-sm">No hay reseñas para esta sesión.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($session->reviews as $review)
                                <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                                                {{ substr($review->author->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $review->author->name }}</p>
                                                <div class="flex items-center mt-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                    <span class="ml-2 text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($session->status === 'completed' && !$session->reviews->where('author_id', auth()->id())->first())
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <button @click="showReviewModal = true" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus mr-2"></i> Añadir Reseña
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Tarjeta de Información del Estudiante -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Estudiante</h3>
                </div>
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="flex justify-center mb-4">
                        <img src="{{ $session->mentee->profile_photo_url }}" 
                             alt="{{ $session->mentee->name }}" 
                             class="h-24 w-24 rounded-full bg-gray-100">
                    </div>
                    <h4 class="text-lg font-medium text-gray-900">{{ $session->mentee->name }}</h4>
                    <p class="text-sm text-gray-500 mt-1">{{ $session->mentee->email }}</p>
                    
                    <div class="mt-6 flex justify-center space-x-3">
                        <a href="mailto:{{ $session->mentee->email }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-envelope mr-1.5 text-gray-500"></i> Correo
                        </a>
                        @if($session->mentee->profile && $session->mentee->profile->phone)
                            <a href="tel:{{ $session->mentee->profile->phone }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-phone mr-1.5 text-gray-500"></i> Llamar
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detalles Adicionales -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Detalles de la Sesión</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID de la Sesión</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Creada el</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->created_at->isoFormat('D [de] MMMM [de] YYYY [a las] h:mm A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->updated_at->isoFormat('D [de] MMMM [de] YYYY [a las] h:mm A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Próximas Sesiones con este Estudiante -->
            @if(isset($upcomingSessions) && $upcomingSessions->isNotEmpty())
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Próximas Sesiones</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <ul class="list-none mb-0">
                            @foreach($upcomingSessions as $upcoming)
                                @if($upcoming->id !== $session->id)
                                    <li class="py-3 sm:py-4 border-b border-gray-100 last:border-0">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $upcoming->title }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $upcoming->start_time->isoFormat('D [de] MMMM [de] YYYY') }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $upcoming->start_time->format('H:i') }} - 
                                                    {{ $upcoming->start_time->copy()->addMinutes($upcoming->duration)->format('H:i') }}
                                                </p>
                                            </div>
                                            <div class="inline-flex items-center">
                                                <a href="{{ route('mentor.sessions.show', $upcoming->id) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Ver detalles
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Cancelación -->
<div x-data="{ showCancelModal: false, cancelReason: '' }" 
     x-show="showCancelModal" 
     class="fixed z-10 inset-0 overflow-y-auto" 
     aria-labelledby="modal-title" 
     x-ref="dialog" 
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fondo del modal -->
        <div x-show="showCancelModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="showCancelModal = false" 
             aria-hidden="true"></div>

        <!-- Contenido del modal -->
        <div x-show="showCancelModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Confirmar Cancelación
                    </h3>
                    <div class="mt-4">
                        <form action="{{ route('mentor.sessions.update-status', $session) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            
                            <p class="text-sm text-gray-500">
                                ¿Estás seguro de que deseas cancelar esta sesión?
                            </p>
                            
                            <div>
                                <label for="cancelReason" class="block text-sm font-medium text-gray-700 mb-1">
                                    Razón de la cancelación (opcional)
                                </label>
                                <textarea id="cancelReason" 
                                          name="notes" 
                                          rows="3"
                                          x-model="cancelReason"
                                          class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md p-2"
                                          placeholder="Proporciona una razón para la cancelación"></textarea>
                            </div>
                            
                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="button" 
                                        @click="showCancelModal = false"
                                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-1 sm:text-sm">
                                    Cerrar
                                </button>
                                <button type="submit" 
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:col-start-2 sm:text-sm">
                                    Confirmar Cancelación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Reseña -->
<div x-data="{ showReviewModal: false, rating: 5, comment: '' }" 
     x-show="showReviewModal" 
     class="fixed z-10 inset-0 overflow-y-auto" 
     aria-labelledby="modal-title" 
     x-ref="dialog" 
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fondo del modal -->
        <div x-show="showReviewModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="showReviewModal = false" 
             aria-hidden="true"></div>

        <!-- Contenido del modal -->
        <div x-show="showReviewModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Añadir Reseña
                    </h3>
                    <div class="mt-2">
                        <form action="{{ route('mentor.sessions.review', $session) }}" method="POST" class="mt-4 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Calificación</label>
                                <div class="flex items-center justify-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" @click="rating = {{ $i }}" class="focus:outline-none">
                                            <svg class="h-8 w-8" :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    @endfor
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>
                                @error('rating')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                                <textarea id="comment" name="comment" rows="4" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md p-2"
                                    placeholder="¿Cómo fue la sesión?"
                                    x-model="comment"
                                    required></textarea>
                                @error('comment')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="submit" 
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                                    Guardar Reseña
                                </button>
                                <button type="button" 
                                        @click="showReviewModal = false"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Inicialización de Alpine.js si es necesario
    });

    // Manejar el modal de cancelación
    document.addEventListener('DOMContentLoaded', function() {
        const cancelButton = document.getElementById('cancelSessionBtn');
        if (cancelButton) {
            cancelButton.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('cancelSessionForm').submit();
            });
        }
    });
</script>
@endpush
