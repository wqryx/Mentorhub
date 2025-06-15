@extends('student.layouts.app')

@section('title', 'Mi Mentor - MentorHub')

@push('styles')
<style>
    .specialty-tag {
        @apply inline-block bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded-full mr-2 mb-2;
    }
    
    .contact-item {
        @apply flex items-center py-2 border-b border-gray-100 last:border-0;
    }
    
    .contact-label {
        @apply font-medium text-gray-700 w-24 flex-shrink-0;
    }
    
    .contact-value, .contact-link {
        @apply text-gray-600;
    }
    
    .contact-link:hover {
        @apply text-blue-600;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mi Mentor</h1>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    @if($mentor)
        <!-- Mentor Profile Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Perfil del Mentor</h2>
            </div>
            
            <div class="p-6">
                <!-- Mentor Header -->
                <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                    <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md" 
                         src="{{ $mentor->profile_photo_url }}" 
                         alt="{{ $mentor->name }}">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $mentor->name }}</h3>
                            @if($mentor->profile && $mentor->profile->title)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $mentor->profile->title }}
                                </span>
                            @endif
                        </div>
                        <p class="text-blue-600">{{ $mentor->email }}</p>
                        @if($mentor->profile && $mentor->profile->bio)
                            <p class="mt-2 text-gray-600">{{ $mentor->profile->bio }}</p>
                        @endif
                    </div>
                </div>

                <!-- Specialties Section -->
                @if($mentor->specialities->isNotEmpty())
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Especialidades</h4>
                        <div class="flex flex-wrap">
                            @foreach($mentor->specialities as $speciality)
                                <span class="specialty-tag">
                                    <i class="fas fa-tag mr-1"></i> {{ $speciality->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Contact Information -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Información de Contacto</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="contact-item">
                            <span class="contact-label"><i class="fas fa-envelope mr-2 text-blue-500"></i> Email:</span>
                            <a href="mailto:{{ $mentor->email }}" class="contact-value hover:text-blue-600">
                                {{ $mentor->email }}
                            </a>
                        </div>
                        
                        @if($mentor->profile && $mentor->profile->phone)
                            <div class="contact-item">
                                <span class="contact-label"><i class="fas fa-phone-alt mr-2 text-blue-500"></i> Teléfono:</span>
                                <a href="tel:{{ $mentor->profile->phone }}" class="contact-value">
                                    {{ $mentor->profile->phone }}
                                </a>
                            </div>
                        @endif
                        
                        @if($mentor->profile && $mentor->profile->linkedin_url)
                            <div class="contact-item">
                                <span class="contact-label"><i class="fab fa-linkedin mr-2 text-blue-500"></i> LinkedIn:</span>
                                <a href="{{ $mentor->profile->linkedin_url }}" target="_blank" class="contact-link">
                                    {{ parse_url($mentor->profile->linkedin_url, PHP_URL_HOST) }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Next Session -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Próxima Sesión</h4>
                    @php
                        $nextSession = $mentor->events()
                            ->where('start_date', '>=', now())
                            ->orderBy('start_date')
                            ->first();
                    @endphp
                    
                    @if($nextSession)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-0.5">
                                    <i class="far fa-calendar-alt text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h5 class="text-lg font-medium text-gray-900">{{ $nextSession->title }}</h5>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <i class="far fa-clock mr-1"></i> 
                                        {{ \Carbon\Carbon::parse($nextSession->start_date)->format('l, d M Y \\d\\e H:i') }}
                                    </p>
                                    @if($nextSession->description)
                                        <p class="mt-2 text-sm text-gray-600">
                                            {{ $nextSession->description }}
                                        </p>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('student.calendar') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                            <i class="far fa-calendar-alt mr-1"></i> Ver en calendario
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        No hay sesiones programadas actualmente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Send Message Form -->
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Enviar Mensaje</h4>
                    <form action="#" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Asunto</label>
                            <input type="text" name="subject" id="subject" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Mensaje</label>
                            <textarea id="message" name="message" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-paper-plane mr-2"></i> Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- No Mentor Assigned -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                        <i class="fas fa-user-graduate text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Sin mentor asignado</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Actualmente no tienes un mentor asignado. Selecciona un mentor de la lista o contacta al administrador.
                    </p>
                    
                    @if(auth()->user()->is_mentor)
                        <div class="mt-6">
                            <button onclick="assignMentor({{ $user->id }})" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-user-plus mr-2"></i> Asignarme como Mentor
                            </button>
                        </div>
                    @endif
                    
                    <div class="mt-6">
                        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                            <i class="fas fa-arrow-left mr-1"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        @push('scripts')
        <script>
            function assignMentor(studentId) {
                if(confirm('¿Estás seguro de que deseas asignarte como mentor de este estudiante?')) {
                    fetch(`/mentor/students/${studentId}/assign`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            alert('¡Has sido asignado como mentor exitosamente!');
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error al asignar mentor');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al procesar la solicitud');
                    });
                }
            }
        </script>
        @endpush
    @endif
</div>
@endsection
