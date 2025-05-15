<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 animate-fadeIn">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Saludo personalizado -->
            <div class="mb-8">
                <h1 class="text-2xl font-semibold text-gray-900">¡Bienvenido, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-600">Aquí tienes un resumen de tu actividad reciente.</p>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Estadística 1: Sesiones completadas -->
                <x-stat-card 
                    title="Sesiones completadas" 
                    value="{{ $completedSessions ?? 0 }}" 
                    change="+5% vs. mes pasado" 
                    changeType="increase"
                    link="{{ route('dashboard') }}">
                    <x-slot name="icon">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </x-slot>
                </x-stat-card>
                
                <!-- Estadística 2: Próximas sesiones -->
                <x-stat-card 
                    title="Próximas sesiones" 
                    value="{{ $upcomingSessions ?? 0 }}" 
                    link="{{ route('dashboard') }}">
                    <x-slot name="icon">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </x-slot>
                </x-stat-card>
                
                <!-- Estadística 3: Mentores disponibles -->
                <x-stat-card 
                    title="Mentores disponibles" 
                    value="{{ $availableMentors ?? 0 }}" 
                    change="+2 nuevos" 
                    changeType="increase"
                    link="{{ route('dashboard') }}">
                    <x-slot name="icon">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </x-slot>
                </x-stat-card>
                
                <!-- Estadística 4: Calificación promedio -->
                <x-stat-card 
                    title="Calificación promedio" 
                    value="{{ $averageRating ?? '4.8' }}" 
                    link="{{ route('dashboard') }}">
                    <x-slot name="icon">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </x-slot>
                </x-stat-card>
            </div>

            <!-- Contenido principal en dos columnas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Columna 1: Actividad reciente y acciones rápidas (2/3 del ancho) -->
                <div class="lg:col-span-2 grid gap-8">
                    <!-- Acciones rápidas -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones rápidas</h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <!-- Acción: Buscar mentor -->
                                <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                    <div class="bg-blue-100 p-3 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-center">Buscar mentor</span>
                                </a>
                                
                                <!-- Acción: Programar sesión -->
                                <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                    <div class="bg-green-100 p-3 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-center">Programar sesión</span>
                                </a>
                                
                                <!-- Acción: Ver cursos -->
                                <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                    <div class="bg-indigo-100 p-3 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-center">Ver cursos</span>
                                </a>
                                
                                <!-- Acción: Mi perfil -->
                                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                    <div class="bg-yellow-100 p-3 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-center">Mi perfil</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actividad reciente -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Actividad reciente</h3>
                            
                            <div class="divide-y divide-gray-100">
                                <!-- Usando el componente de actividad -->
                                <x-activity-item 
                                    type="session"
                                    description="Sesión de mentoría programada con <span class='font-medium'>Laura García</span>"
                                    time="Hace 3 horas"
                                />
                                
                                <x-activity-item 
                                    type="message"
                                    description="Nuevo mensaje de <span class='font-medium'>Carlos Méndez</span>"
                                    time="Ayer"
                                    user="Carlos Méndez"
                                    userAvatar="https://randomuser.me/api/portraits/men/32.jpg"
                                />
                                
                                <x-activity-item 
                                    type="review"
                                    description="Recibiste una calificación de 5 estrellas por tu sesión de mentoría"
                                    time="Hace 2 días"
                                />
                            </div>
                            
                            <div class="mt-6 text-center">
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Ver toda la actividad</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Columna 2: Próximas sesiones y calendario (1/3 del ancho) -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg h-full">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Próximas sesiones</h3>
                            
                            <div class="space-y-4">
                                <!-- Usando el componente de sesiones próximas -->
                                <x-upcoming-session 
                                    date="10"
                                    month="Mayo"
                                    title="Desarrollo de Carrera"
                                    timeRange="10:00 - 11:00 AM"
                                    mentorName="María López"
                                    mentorAvatar="https://randomuser.me/api/portraits/women/23.jpg"
                                />
                                
                                <x-upcoming-session 
                                    date="12"
                                    month="Mayo"
                                    title="Liderazgo y Gestión"
                                    timeRange="15:30 - 16:30 PM"
                                    mentorName="Juan Pérez"
                                    mentorAvatar="https://randomuser.me/api/portraits/men/45.jpg"
                                />
                                
                                <x-upcoming-session 
                                    date="15"
                                    month="Mayo"
                                    title="Desarrollo Web Avanzado"
                                    timeRange="09:00 - 10:30 AM"
                                    mentorName="Ana García"
                                    mentorAvatar="https://randomuser.me/api/portraits/women/66.jpg"
                                />
                            </div>
                            
                            <div class="mt-6 text-center">
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Ver todas las sesiones</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
