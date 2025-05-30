@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Mi Mentor</h1>
    
    @if($mentor)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <img class="h-16 w-16 rounded-full" src="{{ $mentor->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $mentor->name }}">
                </div>
                <div>
                    <h2 class="text-xl font-semibold">{{ $mentor->name }}</h2>
                    <p class="text-gray-600">{{ $mentor->email }}</p>
                    <p class="text-gray-600">{{ $mentor->profile->bio ?? 'No hay biografía disponible' }}</p>
                </div>
            </div>
            
            <div class="mt-6">
                <h3 class="text-lg font-medium mb-2">Especialidades</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($mentor->specialties ?? [] as $specialty)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            {{ $specialty->name }}
                        </span>
                    @empty
                        <p class="text-gray-500">No hay especialidades registradas.</p>
                    @endforelse
                </div>
            </div>
            
            <div class="mt-6">
                <h3 class="text-lg font-medium mb-2">Contacto</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Email:</span> {{ $mentor->email }}</p>
                    @if($mentor->profile && $mentor->profile->phone)
                        <p><span class="font-medium">Teléfono:</span> {{ $mentor->profile->phone }}</p>
                    @endif
                    @if($mentor->profile && $mentor->profile->linkedin_url)
                        <p>
                            <span class="font-medium">LinkedIn:</span> 
                            <a href="{{ $mentor->profile->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $mentor->profile->linkedin_url }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="mt-6">
                <h3 class="text-lg font-medium mb-2">Próxima Sesión</h3>
                @php
                    $nextSession = \App\Models\Event::where('mentor_id', $mentor->id)
                        ->where('start_date', '>=', now())
                        ->orderBy('start_date')
                        ->first();
                @endphp
                
                @if($nextSession)
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="font-medium">{{ $nextSession->title }}</p>
                        <p class="text-gray-600">{{ \Carbon\Carbon::parse($nextSession->start_date)->format('d/m/Y H:i') }}</p>
                        <p class="mt-2">{{ $nextSession->description }}</p>
                        <a href="{{ route('student.calendar') }}" class="inline-block mt-2 text-blue-600 hover:underline">Ver en calendario</a>
                    </div>
                @else
                    <p class="text-gray-500">No hay sesiones programadas.</p>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Enviar Mensaje</h2>
            <form action="#" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Asunto</label>
                    <input type="text" name="subject" id="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label>
                    <textarea name="message" id="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Enviar Mensaje
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No tienes un mentor asignado actualmente. Por favor, contacta con el administrador para que te asigne un mentor.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
