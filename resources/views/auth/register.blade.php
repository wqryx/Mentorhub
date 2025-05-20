<x-guest-layout>
    @php
    // Determinar el tipo de usuario desde la URL
    $userType = request()->query('type', 'default');

    // Configurar colores y textos según el tipo de usuario
    switch($userType) {
        case 'mentor':
            $primaryColor = 'green';
            $bgClass = 'bg-green-600';
            $gradientClass = 'from-green-600 to-green-800';
            $hoverClass = 'hover:bg-green-700';
            $ringClass = 'focus:ring-green-500';
            $borderClass = 'focus:border-green-500';
            $textClass = 'text-green-600';
            $hoverTextClass = 'hover:text-green-800';
            $title = 'Registro de Mentor';
            $description = 'Crea tu cuenta de mentor y comienza a compartir tu conocimiento';
            $icon = '<path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>';
            break;
        case 'student':
            $primaryColor = 'purple';
            $bgClass = 'bg-purple-600';
            $gradientClass = 'from-purple-600 to-purple-800';
            $hoverClass = 'hover:bg-purple-700';
            $ringClass = 'focus:ring-purple-500';
            $borderClass = 'focus:border-purple-500';
            $textClass = 'text-purple-600';
            $hoverTextClass = 'hover:text-purple-800';
            $title = 'Registro de Estudiante';
            $description = 'Crea tu cuenta de estudiante y comienza a aprender';
            $icon = '<path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>';
            break;
        default:
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
    }
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

                        <!-- Hidden field para almacenar el tipo de usuario -->
                        <input type="hidden" name="user_type" value="{{ $userType }}">

                        <!-- Botón de registro y enlace de retorno -->
                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white {{ $bgClass }} {{ $hoverClass }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $ringClass }} transition-all duration-200 hover:shadow-lg">
                                {{ __('Crear cuenta') }}
                            </button>
                            <div class="text-center mt-8 border-t border-gray-200 pt-6">
                                <p class="text-sm text-gray-600 mb-1">
                                    {{ __('¿Ya tienes una cuenta?') }}
                                </p>
                                <a href="{{ route('login') }}?type={{ $userType }}" class="inline-block w-full py-2.5 px-4 border border-gray-300 rounded-lg text-sm font-medium {{ $textClass }} hover:bg-gray-50 transition-all duration-200 text-center">
                                    {{ __('Iniciar sesión') }}
                                </a>
                            </div>
                        </div>
    </form>
</x-guest-layout>
