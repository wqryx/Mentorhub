<x-guest-layout>
    <div class="flex min-h-screen w-full">
        <!-- Panel lateral izquierdo (azul) -->
        <div class="hidden md:block md:w-2/5 bg-blue-600 relative">
            <div class="flex flex-col justify-center items-center h-full text-white px-10 py-16 text-center">
                <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 13L9 17L19 7" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2">Iniciar sesión</h1>
                <p class="text-lg opacity-90 mb-8 max-w-xs">Bienvenido a MentorHub. Tu plataforma educativa.</p>
                <a href="/" class="mt-4 border-2 border-white rounded-full py-2 px-6 text-sm font-medium hover:bg-white hover:bg-opacity-10 transition-all">
                    Volver al inicio
                </a>
            </div>
        </div>

        <!-- Panel derecho (formulario de login) -->
        <div class="w-full md:w-3/5 bg-white">
            <div class="max-w-md mx-auto px-6 py-12 flex flex-col justify-center h-full">
                <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">Iniciar sesión</h2>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-600 mb-2">
                            Correo electrónico
                        </label>
                        <input id="email" name="email" type="email" 
                            class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="ejemplo@correo.com" 
                            required autofocus autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-medium text-gray-600">
                                Contraseña
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-blue-500 hover:text-blue-700">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                        <input id="password" name="password" type="password" 
                            class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="••••••••" 
                            required autocomplete="current-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mb-4">
                        <input id="remember_me" name="remember" type="checkbox" 
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-600">
                            Recordarme
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            INICIAR SESIÓN
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Crear una nueva cuenta
                        </a>
                    </div>

                    <div class="mt-6 text-center text-xs text-gray-400">
                        © MentorHub {{ date('Y') }} | Todos los derechos reservados
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        .bg-blue-600 {
            background-color: #2563eb;
        }
        .hover\:bg-blue-700:hover {
            background-color: #1d4ed8;
        }
        .focus\:ring-blue-500:focus {
            --tw-ring-color: #3b82f6;
        }
        .focus\:border-blue-500:focus {
            border-color: #3b82f6;
        }
        .text-blue-600 {
            color: #2563eb;
        }
        .text-blue-500 {
            color: #3b82f6;
        }
        .hover\:text-blue-800:hover {
            color: #1e40af;
        }
    </style>
</x-guest-layout>
