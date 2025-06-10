@extends('layouts.guest')

@section('content')
    @php
    // Configuración de estilos para el formulario
    $primaryColor = 'blue';
    $bgClass = 'bg-blue-600';
    $gradientClass = 'from-blue-600 to-blue-800';
    $hoverClass = 'hover:bg-blue-700';
    $ringClass = 'focus:ring-blue-500';
    $borderClass = 'focus:border-blue-500';
    $textClass = 'text-blue-600';
    $hoverTextClass = 'hover:text-blue-800';
    $title = 'Registro';
    $description = 'Crea una nueva cuenta';
    $icon = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path>';
    @endphp

    <div class="min-h-screen flex flex-col justify-center bg-gray-50">
        <div class="flex flex-col md:flex-row">
            <!-- Banner lateral con imagen y degradado de color -->
            <div class="hidden md:block md:w-1/2 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1171&q=80')">
                <div class="absolute inset-0 bg-gradient-to-r {{ $gradientClass }} opacity-90"></div>
                <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-12">
                    <div class="max-w-md text-center">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            {!! $icon !!}
                        </svg>
                        <h1 class="text-3xl font-bold mb-4">{{ __($title) }}</h1>
                        <p class="text-lg mb-6">{{ __($description) }}</p>
                        <div class="flex justify-center space-x-4">
                            <a href="/" class="py-2 px-4 border border-white rounded-md hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                                Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Formulario de registro -->
            <div class="w-full md:w-1/2 px-4 py-12 sm:px-6 lg:px-8">
                <div class="max-w-md mx-auto">
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold {{ $textClass }}">{{ $title }}</h1>
                    </div>
                    
                    <div class="md:hidden text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ __($title) }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ __($description) }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Nombre completo') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 {{ $textClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="name" name="name" type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none {{ $ringClass }} {{ $borderClass }} transition-all duration-200" placeholder="Tu nombre completo" value="{{ old('name') }}" required autofocus autocomplete="name">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Correo electrónico') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 {{ $textClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none {{ $ringClass }} {{ $borderClass }} transition-all duration-200" placeholder="nombre@ejemplo.com" value="{{ old('email') }}" required autocomplete="username">
                            </div>
                            <p class="mt-1 text-xs {{ $textClass }}">Necesario para la verificación de cuenta</p>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Contraseña') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 {{ $textClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none {{ $ringClass }} {{ $borderClass }} transition-all duration-200" placeholder="••••••••" required autocomplete="new-password">
                            </div>
                            <p class="mt-1 text-xs {{ $textClass }}">Mínimo 8 caracteres</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Confirmar contraseña') }}
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 {{ $textClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none {{ $ringClass }} {{ $borderClass }} transition-all duration-200" placeholder="••••••••" required autocomplete="new-password">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Términos y Condiciones -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input id="terms" name="terms" type="checkbox" class="h-5 w-5 {{ $textClass }} {{ $ringClass }} border-gray-300 rounded transition-colors" required>
                                <label for="terms" class="ml-2 block text-sm text-gray-700">
                                    {{ __('Acepto los') }} <a href="#" class="{{ $textClass }} {{ $hoverTextClass }} underline">Términos de Servicio</a> {{ __('y la') }} <a href="#" class="{{ $textClass }} {{ $hoverTextClass }} underline">Política de Privacidad</a>
                                </label>
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Tipo de cuenta') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Student Option -->
                                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:border-{{ $primaryColor }}-300 transition-colors duration-200">
                                    <input type="radio" name="role" value="student" class="sr-only peer" 
                                           {{ old('role') === 'student' || !old('role') ? 'checked' : '' }}>
                                    <div class="absolute top-2 right-2 text-{{ $primaryColor }}-600 hidden peer-checked:block">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="p-2 mr-3 rounded-full bg-{{ $primaryColor }}-100 text-{{ $primaryColor }}-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">Estudiante</h3>
                                            <p class="text-sm text-gray-500">Accede a todos los cursos y recursos</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Mentor Option -->
                                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:border-{{ $primaryColor }}-300 transition-colors duration-200">
                                    <input type="radio" name="role" value="mentor" class="sr-only peer" 
                                           {{ old('role') === 'mentor' ? 'checked' : '' }}>
                                    <div class="absolute top-2 right-2 text-{{ $primaryColor }}-600 hidden peer-checked:block">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="p-2 mr-3 rounded-full bg-{{ $primaryColor }}-100 text-{{ $primaryColor }}-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m14-6h2m-2 6h2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">Mentor</h3>
                                            <p class="text-sm text-gray-500">Comparte tu conocimiento y experiencia</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Admin Approval Notice (Conditional) -->
                        <div id="mentorApprovalNotice" class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 hidden">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        {{ __('Las cuentas de mentor requieren aprobación. Revisaremos tu solicitud y te notificaremos por correo electrónico una vez aprobada.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" name="terms" type="checkbox" class="h-4 w-4 {{ $textClass }} {{ $ringClass }} border-gray-300 rounded" required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="text-gray-700">
                                        {{ __('Acepto los') }} <a href="{{ route('terms') }}" target="_blank" class="font-medium {{ $textClass }} hover:underline">Términos de Servicio</a> {{ __('y la') }} <a href="{{ route('privacy.policy') }}" target="_blank" class="font-medium {{ $textClass }} hover:underline">Política de Privacidad</a>
                                    </label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                        </div>

                        <!-- Botón de registro y enlace de retorno -->
                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white {{ $bgClass }} {{ $hoverClass }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $ringClass }} transition-all duration-200 hover:shadow-lg">
                                {{ __('Crear cuenta') }}
                            </button>
                            <div class="text-center mt-8 border-t border-gray-200 pt-6">
                                <p class="text-sm text-gray-600 mb-1">
                                    {{ __('¿Ya tienes una cuenta?') }}
                                </p>
                                <a href="{{ route('login') }}" class="inline-block w-full py-2.5 px-4 border border-gray-300 rounded-lg text-sm font-medium {{ $textClass }} hover:bg-gray-50 transition-all duration-200 text-center">
                                    {{ __('Iniciar sesión') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mentorRadio = document.querySelector('input[value="mentor"]');
            const studentRadio = document.querySelector('input[value="student"]');
            const mentorNotice = document.getElementById('mentorApprovalNotice');

            // Show/hide mentor approval notice based on selection
            function toggleMentorNotice() {
                if (mentorRadio.checked) {
                    mentorNotice.classList.remove('hidden');
                } else {
                    mentorNotice.classList.add('hidden');
                }
            }

            // Add event listeners
            mentorRadio.addEventListener('change', toggleMentorNotice);
            studentRadio.addEventListener('change', toggleMentorNotice);

            // Initial check in case of form validation errors
            toggleMentorNotice();
        });
    </script>
    @endpush
@endsection
