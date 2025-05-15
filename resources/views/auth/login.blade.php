<x-guest-layout>
    @php
    // Determinar el tipo de usuario desde la URL
    $userType = request()->query('type', 'default');

    // Configurar colores y textos según el tipo de usuario
    switch($userType) {
        case 'admin':
            $primaryColor = 'blue';
            $bgClass = 'bg-blue-600';
            $gradientClass = 'from-blue-600 to-blue-800'; 
            $hoverClass = 'hover:bg-blue-700';
            $ringClass = 'focus:ring-blue-500';
            $borderClass = 'focus:border-blue-500';
            $textClass = 'text-blue-600';
            $hoverTextClass = 'hover:text-blue-800';
            $title = 'Acceso Administrador';
            $description = 'Accede con tus credenciales de administrador';
            $icon = '<path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>';
            break;
        case 'mentor':
            $primaryColor = 'green';
            $bgClass = 'bg-green-600';
            $gradientClass = 'from-green-600 to-green-800';
            $hoverClass = 'hover:bg-green-700';
            $ringClass = 'focus:ring-green-500';
            $borderClass = 'focus:border-green-500';
            $textClass = 'text-green-600';
            $hoverTextClass = 'hover:text-green-800';
            $title = 'Acceso Mentor';
            $description = 'Accede con tus credenciales de mentor';
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
            $title = 'Acceso Estudiante';
            $description = 'Accede con tus credenciales de estudiante';
            $icon = '<path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>';
            break;
        case 'guest':
            $primaryColor = 'gray';
            $bgClass = 'bg-gray-600';
            $gradientClass = 'from-gray-600 to-gray-800';
            $hoverClass = 'hover:bg-gray-700';
            $ringClass = 'focus:ring-gray-500';
            $borderClass = 'focus:border-gray-500';
            $textClass = 'text-gray-600';
            $hoverTextClass = 'hover:text-gray-800';
            $title = 'Acceso Invitado';
            $description = 'Accede como invitado para explorar nuestra plataforma';
            $icon = '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>';
            break;
        default:
            $primaryColor = 'indigo';
            $bgClass = 'bg-indigo-600';
            $gradientClass = 'from-indigo-600 to-indigo-800';
            $hoverClass = 'hover:bg-indigo-700';
            $ringClass = 'focus:ring-indigo-500';
            $borderClass = 'focus:border-indigo-500';
            $textClass = 'text-indigo-600';
            $hoverTextClass = 'hover:text-indigo-800';
            $title = 'Iniciar sesión';
            $description = 'Bienvenido de vuelta, accede a tu cuenta';
            $icon = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path>';
            break;
    }

    // Determinar si se debe mostrar el enlace de registro
    $showRegisterLink = $userType !== 'admin' && $userType !== 'guest';
    @endphp

    <div class="min-h-screen flex flex-col justify-center bg-gray-50">
        <div class="flex flex-col md:flex-row">
            <!-- Banner lateral con imagen y degradado de color -->
            <div class="hidden md:block md:w-1/2 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1505751172876-fa1923c5c528?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80')">
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
            
            <!-- Formulario de inicio de sesión -->
            <div class="w-full md:w-1/2 px-4 py-12 sm:px-6 lg:px-8">
                <div class="max-w-md mx-auto">
                    <div class="flex justify-center mb-8">
                        <a href="/" class="flex items-center space-x-2">
                            <svg class="w-10 h-10 {{ $textClass }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                {!! $icon !!}
                            </svg>
                            <span class="text-2xl font-bold {{ $textClass }}">MentorHub</span>
                        </a>
                    </div>
                    
                    <div class="md:hidden text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ __($title) }}
                        </h2>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ __($description) }}
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

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
                                <input id="email" name="email" type="email" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none {{ $ringClass }} {{ $borderClass }} transition-all duration-200" placeholder="nombre@ejemplo.com" value="{{ old('email') }}" required autofocus autocomplete="username">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    {{ __('Contraseña') }}
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-medium {{ $textClass }} {{ $hoverTextClass }} transition-colors">
                                        {{ __('¿Olvidaste tu contraseña?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 {{ $textClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none {{ $ringClass }} {{ $borderClass }} transition-all duration-200" placeholder="••••••••" required autocomplete="current-password">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center mb-6">
                            <input id="remember_me" name="remember" type="checkbox" class="h-5 w-5 {{ $textClass }} {{ $ringClass }} border-gray-300 rounded transition-colors">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                {{ __('Recordarme') }}
                            </label>
                        </div>

                        <!-- Login Button -->
                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white {{ $bgClass }} {{ $hoverClass }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $ringClass }} transition-all duration-200 hover:shadow-lg">
                                {{ __('Iniciar sesión') }}
                            </button>
                        </div>
                
                        <!-- Register Link -->
                        @if($userType != 'admin' && $userType != 'guest')
                        <div class="text-center mt-8 border-t border-gray-200 pt-6">
                            <p class="text-sm text-gray-600 mb-3">
                                {{ __('¿No tienes una cuenta?') }}
                            </p>
                            <a href="{{ route('register') }}?type={{ $userType }}" class="inline-block w-full py-3 px-4 border border-gray-300 rounded-lg text-sm font-medium {{ $textClass }} hover:bg-gray-50 transition-all duration-200 text-center">
                                {{ __('Crear cuenta nueva') }}
                            </a>
                            <p class="text-xs text-gray-500 mt-4">
                                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Requiere verificación por correo electrónico') }}
                            </p>
                        </div>
                        @elseif($userType == 'admin')
                        <div class="text-center mt-8 border-t border-gray-200 pt-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <svg class="w-5 h-5 text-blue-400 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm text-blue-700 inline-block">
                                    {{ __('El registro de administradores solo es posible desde el panel de administración') }}
                                </p>
                            </div>
                        </div>
                        @elseif($userType == 'guest')
                        <div class="text-center mt-8 border-t border-gray-200 pt-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <svg class="w-5 h-5 text-gray-400 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm text-gray-600 inline-block">
                                    {{ __('Los invitados no requieren registro') }}
                                </p>
                            </div>
                        </div>
                        @endif
                
                <!-- Social Login -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                {{ __('O continúa con') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 hover:border-{{ $primaryColor }}-500 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:text-{{ $primaryColor }}-600 hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032
                                    s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2
                                    C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                            </svg>
                        </a>

                        <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 hover:border-{{ $primaryColor }}-500 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:text-{{ $primaryColor }}-600 hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
