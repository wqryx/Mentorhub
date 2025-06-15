<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - MentorHub')</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-blue-600">
                <div class="flex items-center justify-center h-16 px-4 bg-blue-700">
                    <a href="{{ route('student.dashboard') }}" class="text-white text-xl font-bold">MentorHub</a>
                </div>
                <nav class="flex-1 px-2 py-4">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('student.courses') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200">
                        <i class="fas fa-book mr-3"></i>
                        Mis Cursos
                    </a>
                    <a href="{{ route('student.mentor') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200">
                        <i class="fas fa-chalkboard-teacher mr-3"></i>
                        Mentores
                    </a>
                    <a href="{{ route('student.notifications') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200">
                        <i class="fas fa-bell mr-3"></i>
                        Notificaciones
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navbar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center space-x-1">
                        <button class="md:hidden text-gray-500 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-bars"></i>
                        </button>
                        <nav class="hidden md:flex space-x-1" aria-label="Navegación principal">
                            <a href="{{ route('student.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('student.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-tachometer-alt mr-1"></i> Tablero
                            </a>
                            <a href="{{ route('student.courses') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('student.courses*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-book mr-1"></i> Cursos
                            </a>
                            <a href="{{ route('student.mentor') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('student.mentor*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-chalkboard-teacher mr-1"></i> Mentores
                            </a>
                            <a href="{{ route('student.notifications') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('student.notifications*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-bell mr-1"></i> Notificaciones
                            </a>
                        </nav>
                        <h1 class="ml-4 text-lg font-medium text-gray-900 md:hidden">@yield('title')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative ml-3" x-data="{ open: false }">
                            <div class="flex items-center">
                                <button @click="open = !open" class="flex items-center text-sm focus:outline-none" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <div class="relative h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-indigo-500" 
                                         src="{{ Auth::user()->photo_url }}" 
                                         alt="{{ Auth::user()->name }}"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}'">
                                        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                                    </div>
                                </button>
                            </div>
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1" role="none">
                                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                            <i class="fas fa-user mr-2 text-gray-500"></i> Mi perfil
                                        </a>
                                        <a href="{{ route('student.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                            <i class="fas fa-cog mr-2 text-gray-500"></i> Configuración
                                        </a>
                                    </div>
                                    <div class="py-1 border-t border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <script>
        // Close dropdown when clicking outside
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdown', () => ({
                open: false,
                toggle() {
                    this.open = !this.open;
                }
            }));
        });
    </script>
    @stack('scripts')
</body>
</html>
