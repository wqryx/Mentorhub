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
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-blue-700">
                    <a href="{{ route('admin.dashboard') }}" class="text-white text-xl font-bold">MentorHub</a>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-2 py-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        Usuarios
                    </a>
                    
                    <a href="{{ route('admin.courses.index') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200 {{ request()->routeIs('admin.courses.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-book mr-3"></i>
                        Cursos
                    </a>
                    
                    <a href="{{ route('admin.events.index') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200 {{ request()->routeIs('admin.events.*') ? 'bg-blue-700' : '' }}">
                        <i class="far fa-calendar-alt mr-3"></i>
                        Eventos
                    </a>
                    
                    <a href="{{ route('admin.notifications.index') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200 {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-700' : '' }}">
                        <i class="far fa-bell mr-3"></i>
                        Notificaciones
                    </a>
                    
                    <a href="{{ route('admin.settings.general') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 rounded-md mb-1 transition-colors duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-cog mr-3"></i>
                        Configuración
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navbar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center space-x-1">
                        <button class="md:hidden text-gray-500 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="far fa-bell text-xl"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                                @endif
                            </button>
                            
                            <!-- Notification dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-bell text-blue-500"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $notification->data['title'] ?? 'Nueva notificación' }}</p>
                                                        <p class="text-xs text-gray-500">{{ $notification->data['message'] ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('admin.notifications.index') }}" class="block px-4 py-2 text-sm text-center text-blue-600 hover:bg-gray-100" role="menuitem">
                                            Ver todas las notificaciones
                                        </a>
                                    @else
                                        <p class="px-4 py-2 text-sm text-gray-500">No hay notificaciones nuevas</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- User menu -->
                        <div class="relative ml-3" x-data="{ open: false }">
                            <div class="flex items-center">
                                <button @click="open = !open" class="flex items-center text-sm focus:outline-none" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <div class="relative h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ Auth::user()->profile_photo_url }}" 
                                             alt="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="ml-3 text-left">
                                        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">
                                            {{ ucfirst(Auth::user()->role) }}
                                        </p>
                                    </div>
                                    <i class="fas fa-chevron-down ml-1 text-gray-400 text-xs"></i>
                                </button>
                            </div>
                            
                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false" 
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                                 role="menu" 
                                 aria-orientation="vertical" 
                                 aria-labelledby="user-menu-button" 
                                 tabindex="-1">
                                <div class="py-1" role="none">
                                    <a href="{{ route('admin.profile.edit') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                       role="menuitem" 
                                       tabindex="-1">
                                        <i class="fas fa-user-circle mr-2"></i> Perfil
                                    </a>
                                    <a href="{{ route('admin.settings.general') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                       role="menuitem" 
                                       tabindex="-1">
                                        <i class="fas fa-cog mr-2"></i> Configuración
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                                role="menuitem" 
                                                tabindex="-1">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    @stack('scripts')
</body>
</html>
