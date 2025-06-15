@extends('student.layouts.app')

@section('title', 'Configuración de la Cuenta')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configuración de la Cuenta</h1>
            <p class="mt-1 text-sm text-gray-500">Administra tu configuración y preferencias personales</p>
        </div>
    </div>

    <!-- Mensajes de éxito -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Menú lateral mejorado -->
        <div class="w-full lg:w-72 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Configuración</h2>
                    <p class="text-sm text-gray-500 mt-1">Administra tus preferencias</p>
                </div>
                <nav class="p-2">
                    <a href="#account-section" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg mb-1 transition-all duration-200 bg-blue-50 text-blue-700">
                        <i class="fas fa-user-circle w-5 h-5 mr-3 text-blue-600"></i>
                        <span>Mi Cuenta</span>
                        <i class="fas fa-chevron-right ml-auto text-blue-400 text-xs"></i>
                    </a>
                    <a href="#notifications-section" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg mb-1 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-bell w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Notificaciones</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-300 text-xs"></i>
                    </a>
                    <a href="#privacy-section" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg mb-1 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-lock w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Privacidad</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-300 text-xs"></i>
                    </a>
                    <a href="#appearance-section" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg mb-1 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-paint-brush w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Apariencia</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-300 text-xs"></i>
                    </a>
                    <a href="#learning-section" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg mb-1 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-graduation-cap w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Aprendizaje</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-300 text-xs"></i>
                    </a>
                    <a href="#security-section" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-shield-alt w-5 h-5 mr-3 text-gray-500"></i>
                        <span>Seguridad</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-300 text-xs"></i>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenido principal mejorado -->
        <div class="flex-1 space-y-6">
            <!-- Sección de Cuenta -->
            <div id="account-section" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-user-edit text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Información de la cuenta</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Actualiza tu información personal y de contacto</p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Foto de perfil -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Foto de perfil</label>
                                <div class="mt-2 flex items-center">
                                    <div class="relative">
                                        @if(auth()->user()->profile && auth()->user()->profile->avatar)
                                            <img class="h-20 w-20 rounded-full object-cover" src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="{{ auth()->user()->name }}">
                                        @else
                                            <div class="h-20 w-20 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-1 -right-1 bg-white p-1 rounded-full shadow">
                                            <label for="avatar" class="cursor-pointer text-blue-600 hover:text-blue-500">
                                                <i class="fas fa-camera"></i>
                                                <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="ml-4 text-sm">
                                        <p class="text-gray-500">JPG, PNG o GIF. Máx. 2MB</p>
                                    </div>
                                </div>
                                @error('avatar')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nombre -->
                            <div class="sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" autocomplete="name" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Correo electrónico -->
                            <div class="sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" autocomplete="email" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 @enderror">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <div class="mt-1">
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->profile->phone ?? '') }}" autocomplete="tel" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('phone') border-red-300 @enderror">
                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Botón de guardar -->
                            <div class="sm:col-span-6 pt-4 border-t border-gray-200">
                                <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Guardar cambios
                    </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Sección de Notificaciones -->
            <div id="notifications-section" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-100 text-purple-600 mr-4">
                            <i class="fas fa-bell text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Preferencias de notificaciones</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Controla cómo y cuándo recibes notificaciones</p>
                        </div>
                    </div>
                </div>
                        <div class="px-4 py-5 sm:p-6">
                            <form action="{{ route('student.settings.update-notifications') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="space-y-4">
                                    <!-- Notificaciones por correo -->
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="email_notifications" name="email_notifications" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" @if(($user->notification_preferences['email_notifications'] ?? true) === true) checked @endif>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="email_notifications" class="font-medium text-gray-700">Recibir notificaciones por correo electrónico</label>
                                            <p class="text-gray-500">Recibirás actualizaciones importantes por correo electrónico.</p>
                                        </div>
                                    </div>

                                    <!-- Notificaciones push -->
                                    <div class="flex items-start pt-4 border-t border-gray-100">
                                        <div class="flex items-center h-5">
                                            <input id="push_notifications" name="push_notifications" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" @if(($user->notification_preferences['push_notifications'] ?? true) === true) checked @endif>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="push_notifications" class="font-medium text-gray-700">Recibir notificaciones en el navegador</label>
                                            <p class="text-gray-500">Recibirás notificaciones en tiempo real en tu navegador.</p>
                                        </div>
                                    </div>

                                    <!-- Botón de guardar -->
                                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Guardar preferencias
                                        </button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
                
                <!-- Sección de Privacidad -->
                <div id="privacy-section" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-lock text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Configuración de privacidad</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Controla quién puede ver tu información personal</p>
                        </div>
                    </div>
                </div>
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('student.settings.update-privacy') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="space-y-6">
                                <!-- Visibilidad del perfil -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Visibilidad del perfil</label>
                                    <div class="space-y-2">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="visibility_public" name="profile_visibility" type="radio" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" value="public" @if(($user->privacy_settings['profile_visibility'] ?? 'students') === 'public') checked @endif>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="visibility_public" class="font-medium text-gray-700">Público</label>
                                                <p class="text-gray-500">Todos los usuarios pueden ver tu perfil</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="visibility_students" name="profile_visibility" type="radio" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" value="students" @if(($user->privacy_settings['profile_visibility'] ?? 'students') === 'students') checked @endif>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="visibility_students" class="font-medium text-gray-700">Solo estudiantes y profesores</label>
                                                <p class="text-gray-500">Solo los usuarios registrados pueden ver tu perfil</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="visibility_private" name="profile_visibility" type="radio" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" value="private" @if(($user->privacy_settings['profile_visibility'] ?? 'students') === 'private') checked @endif>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="visibility_private" class="font-medium text-gray-700">Privado</label>
                                                <p class="text-gray-500">Solo tú puedes ver tu perfil</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Visibilidad de información de contacto -->
                                <div class="pt-4 border-t border-gray-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Visibilidad de la información de contacto</label>
                                    <div class="space-y-3">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show_email" name="show_email" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" @if($user->privacy_settings['show_email'] ?? true) checked @endif>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show_email" class="font-medium text-gray-700">Mostrar correo electrónico</label>
                                                <p class="text-gray-500">Otros usuarios podrán ver tu dirección de correo electrónico</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show_phone" name="show_phone" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" @if($user->privacy_settings['show_phone'] ?? true) checked @endif>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show_phone" class="font-medium text-gray-700">Mostrar número de teléfono</label>
                                                <p class="text-gray-500">Otros usuarios podrán ver tu número de teléfono</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Configuración de actividad -->
                                <div class="pt-4 border-t border-gray-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Actividad y presencia</label>
                                    <div class="space-y-3">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show_activity" name="show_activity" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" @if($user->privacy_settings['show_activity'] ?? true) checked @endif>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show_activity" class="font-medium text-gray-700">Mostrar actividad reciente</label>
                                                <p class="text-gray-500">Tus actividades recientes serán visibles en tu perfil</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show_online_status" name="show_online_status" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" @if($user->privacy_settings['show_online_status'] ?? true) checked @endif>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show_online_status" class="font-medium text-gray-700">Mostrar estado en línea</label>
                                                <p class="text-gray-500">Otros usuarios podrán ver cuándo estás en línea</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botón de guardar -->
                                <div class="pt-4 border-t border-gray-100 flex justify-end">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Guardar configuración de privacidad
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
              <!-- Sección de Apariencia -->
<div id="appearance-section" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
        <div class="flex items-center">
            <div class="p-2 rounded-lg bg-amber-100 text-amber-600 mr-4">
                <i class="fas fa-palette text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Preferencias de apariencia</h3>
                <p class="text-sm text-gray-500 mt-0.5">Personaliza la apariencia de tu cuenta</p>
            </div>
        </div>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('student.settings.update-appearance') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <!-- Selección de tema -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Tema</label>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <!-- Tema Claro -->
                    <div class="relative">
                        <input type="radio" name="theme" id="theme_light" value="light" 
                               class="peer hidden" 
                               @if(($user->appearance_settings['theme'] ?? 'light') === 'light') checked @endif>
                        <label for="theme_light" class="block cursor-pointer rounded-lg border border-gray-300 bg-white p-4 text-center hover:border-blue-500 peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-200">
                            <div class="h-24 mb-2 overflow-hidden rounded border">
                                <div class="h-1/3 bg-blue-600"></div>
                                <div class="h-2/3 bg-white"></div>
                            </div>
                            <span class="block font-medium text-gray-900">Claro</span>
                        </label>
                    </div>
                    
                    <!-- Tema Oscuro -->
                    <div class="relative">
                        <input type="radio" name="theme" id="theme_dark" value="dark" 
                               class="peer hidden" 
                               @if(($user->appearance_settings['theme'] ?? 'light') === 'dark') checked @endif>
                        <label for="theme_dark" class="block cursor-pointer rounded-lg border border-gray-300 bg-white p-4 text-center hover:border-blue-500 peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-200">
                            <div class="h-24 mb-2 overflow-hidden rounded border border-gray-200">
                                <div class="h-1/3 bg-blue-700"></div>
                                <div class="h-2/3 bg-gray-800"></div>
                            </div>
                            <span class="block font-medium text-gray-900">Oscuro</span>
                        </label>
                    </div>
                    
                    <!-- Tema Automático -->
                    <div class="relative">
                        <input type="radio" name="theme" id="theme_system" value="system" 
                               class="peer hidden" 
                               @if(($user->appearance_settings['theme'] ?? 'light') === 'system') checked @endif>
                        <label for="theme_system" class="block cursor-pointer rounded-lg border border-gray-300 bg-white p-4 text-center hover:border-blue-500 peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-200">
                            <div class="h-24 mb-2 overflow-hidden rounded border">
                                <div class="h-1/3 bg-gradient-to-r from-blue-600 to-blue-400"></div>
                                <div class="h-2/3 bg-gradient-to-r from-white to-gray-100"></div>
                            </div>
                            <span class="block font-medium text-gray-900">Automático (sistema)</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Tamaño del texto -->
            <div class="border-t border-gray-200 pt-6">
                <label for="text_size" class="block text-sm font-medium text-gray-700 mb-2">Tamaño del texto</label>
                <select id="text_size" name="text_size" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="small" @if(($user->appearance_settings['text_size'] ?? 'medium') === 'small') selected @endif>Pequeño</option>
                    <option value="medium" @if(($user->appearance_settings['text_size'] ?? 'medium') === 'medium') selected @endif>Mediano</option>
                    <option value="large" @if(($user->appearance_settings['text_size'] ?? 'medium') === 'large') selected @endif>Grande</option>
                </select>
            </div>
            
            <!-- Botón de guardar -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar preferencias de apariencia
                </button>
            </div>
        </form>
    </div>
</div>
                
                <!-- Sección de Aprendizaje -->
                <div id="learning-section" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-indigo-100 text-indigo-600 mr-4">
                                <i class="fas fa-graduation-cap text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Preferencias de aprendizaje</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Personaliza tu experiencia de aprendizaje</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('student.settings.update-learning') }}" method="POST" class="space-y-8">
                            @csrf
                            @method('PATCH')
                            
                            <!-- Idioma de contenido -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Idioma de contenido preferido</label>
                                <select name="preferred_language" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                    <option value="es" selected>Español</option>
                                    <option value="en">English</option>
                                    <option value="pt">Português</option>
                                    <option value="fr">Français</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Selecciona tu idioma preferido para el contenido de los cursos.</p>
                            </div>
                            
                            <!-- Preferencias de video -->
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">Preferencias de video</label>
                                
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="auto_play" name="auto_play" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="auto_play" class="font-medium text-gray-700">Reproducir videos automáticamente</label>
                                        <p class="text-gray-500">Los videos comenzarán a reproducirse cuando carguen.</p>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="show_subtitles" name="show_subtitles" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="show_subtitles" class="font-medium text-gray-700">Mostrar subtítulos cuando estén disponibles</label>
                                        <p class="text-gray-500">Se mostrarán los subtítulos automáticamente si están disponibles.</p>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="hd_quality" name="hd_quality" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="hd_quality" class="font-medium text-gray-700">Reproducir videos en alta calidad cuando sea posible</label>
                                        <p class="text-gray-500">Se utilizará la mejor calidad disponible según tu conexión.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Visualización de cursos -->
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">Visualización de cursos</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <label class="relative border rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="course_view" value="grid" class="sr-only" checked>
                                        <div class="flex-shrink-0 w-16 h-12 bg-blue-100 rounded flex items-center justify-center mb-2">
                                            <i class="fas fa-th-large text-blue-600"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">Cuadrícula</span>
                                        <div class="absolute top-2 right-2 hidden group-hover:block">
                                            <div class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-check text-blue-600 text-xs"></i>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="relative border rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="course_view" value="list" class="sr-only">
                                        <div class="flex-shrink-0 w-16 h-12 bg-gray-100 rounded flex items-center justify-center mb-2">
                                            <i class="fas fa-list text-gray-600"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Lista</span>
                                    </label>
                                    
                                    <label class="relative border rounded-lg p-4 flex flex-col items-center cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="course_view" value="compact" class="sr-only">
                                        <div class="flex-shrink-0 w-16 h-12 bg-gray-100 rounded flex items-center justify-center mb-2">
                                            <i class="fas fa-grip-lines text-gray-600"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Compacto</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Botón de guardar -->
                            <div class="pt-4 border-t border-gray-200 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar preferencias
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Sección de Seguridad -->
                <div id="security-section" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-red-100 text-red-600 mr-4">
                                <i class="fas fa-shield-alt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Seguridad de la cuenta</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Administra la seguridad de tu cuenta y dispositivos conectados</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-8">
                        <!-- Cambio de contraseña -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-key mr-2 text-blue-500"></i>
                                Cambiar contraseña
                            </h4>
                            
                            <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña actual</label>
                                        <div class="relative">
                                            <input type="password" id="current_password" name="current_password" required
                                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-300 text-red-900 @enderror"
                                                placeholder="Ingresa tu contraseña actual">
                                            @error('current_password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                                        <div class="relative">
                                            <input type="password" id="password" name="password" required
                                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 text-red-900 @enderror"
                                                placeholder="Crea una nueva contraseña">
                                            <p class="mt-1 text-xs text-gray-500">La contraseña debe tener al menos 8 caracteres y contener letras y números.</p>
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar nueva contraseña</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" required
                                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Confirma tu nueva contraseña">
                                    </div>
                                    
                                    <div class="pt-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-save mr-2"></i>
                                            Cambiar contraseña
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Dispositivos conectados -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-laptop-code mr-2 text-indigo-500"></i>
                                    Dispositivos conectados
                                </h4>
                                <button type="button" class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-medium rounded-full text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                                    <i class="fas fa-sign-out-alt mr-1.5"></i>
                                    Cerrar todas las sesiones
                                </button>
                            </div>
                            
                            <p class="text-sm text-gray-500 mb-4">Estos son los dispositivos que actualmente tienen acceso a tu cuenta.</p>
                            
                            <div class="space-y-3">
                                <!-- Dispositivo actual -->
                                <div class="flex items-start p-3 bg-white rounded-lg border border-gray-200 hover:shadow-sm transition-shadow">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3 mt-0.5">
                                        <i class="fas fa-laptop text-blue-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <h5 class="text-sm font-medium text-gray-900">Windows 10 - Chrome</h5>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                                Activo
                                            </span>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            <span>IP: 192.168.1.1</span>
                                            <span class="mx-2 text-gray-300">•</span>
                                            <span>Última actividad: <span class="font-medium">Ahora (Este dispositivo)</span></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Otro dispositivo -->
                                <div class="flex items-start p-3 bg-white rounded-lg border border-gray-200 hover:shadow-sm transition-shadow">
                                    <div class="bg-purple-100 p-2 rounded-lg mr-3 mt-0.5">
                                        <i class="fas fa-mobile-alt text-purple-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <h5 class="text-sm font-medium text-gray-900">iPhone - Safari</h5>
                                            <button type="button" class="inline-flex items-center px-2.5 py-0.5 border border-gray-300 text-xs font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                                Cerrar sesión
                                            </button>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            <span>IP: 192.168.1.2</span>
                                            <span class="mx-2 text-gray-300">•</span>
                                            <span>Última actividad: <span class="font-medium">Hace 2 días</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activar la navegación por pestañas
        var triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="list"]'));
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
        
        // Permitir navegación directa a pestañas con fragmentos de URL
        if (window.location.hash) {
            const hash = window.location.hash;
            const tabTrigger = document.querySelector(`a[href="${hash}"]`);
            if (tabTrigger) {
                new bootstrap.Tab(tabTrigger).show();
            }
        }
    });
</script>
@endpush
