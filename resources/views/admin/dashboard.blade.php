@if(!auth()->check() || !auth()->user()->hasRole('admin'))
    <script>
        window.location.href = "{{ route('admin.login') }}";
    </script>
@endif

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Administrador - MentorHub</title>
    <meta name="description" content="Dashboard de administrador para MentorHub">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1976d2;
            --light-blue: #2196f3;
            --dark-blue: #0d47a1;
            --accent-color: #64b5f6;
            --background-color: #f5f7fa;
            --card-background: #ffffff;
            --text-dark: #263238;
            --text-medium: #546e7a;
            --text-light: #b0bec5;
            --border-color: #eceff1;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-dark);
        }
        
        .top-nav {
            background-color: var(--primary-blue);
            color: white;
        }
        
        .top-nav a {
            color: rgba(255, 255, 255, 0.85);
        }
        
        .top-nav a:hover, .top-nav a.active {
            color: white;
            border-bottom: 2px solid white;
        }
        
        .card {
            background: var(--card-background);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            border-left: 4px solid var(--primary-blue);
        }
        
        .stat-card.green {
            border-left-color: #4caf50;
        }
        
        .stat-card.amber {
            border-left-color: #ff9800;
        }
        
        .stat-card.red {
            border-left-color: #f44336;
        }
        
        .progress-circle {
            position: relative;
            width: 120px;
            height: 120px;
        }
        
        .circle-bg {
            fill: none;
            stroke: #e0e0e0;
            stroke-width: 8;
        }
        
        .circle {
            fill: none;
            stroke: var(--primary-blue);
            stroke-width: 8;
            stroke-linecap: round;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
            transition: stroke-dasharray 0.8s ease;
        }
        
        .percentage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-blue);
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            color: white;
            transition: background-color 0.2s;
        }
        
        .btn-primary:hover {
            background-color: var(--dark-blue);
        }
        
        .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }
        
        /* Nuevos estilos para el diseño moderno */
        .main-container {
            background-color: #f5f7fa;
            border-radius: 15px;
            margin: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .action-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .action-card:hover {
            border-left-color: var(--primary-blue);
            transform: translateX(5px);
        }
        
        .status-pill {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #e0e6ed;
            overflow: hidden;
        }
        
        .progress-value {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, #1976d2 0%, #64b5f6 100%);
        }
    </style>
</head>
<body>
    <!-- Notificación de error -->
    @if(session('error'))
        <div id="error-alert" class="fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg" role="alert">
            <div class="flex items-center">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM10 11.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm0-8.5a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V4a1 1 0 0 0-1-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Error</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
                <button class="ml-auto" onclick="document.getElementById('error-alert').remove()">
                    <svg class="fill-current h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="main-container">
        <!-- Barra de navegación superior azul -->
        <nav class="top-nav flex items-center h-16 px-4 sm:px-6 lg:px-8">
            <div class="flex-1 flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold mr-10">MentorHub</a>
                
                <!-- Enlaces de navegación principales -->
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="active py-2 text-sm font-medium">Tablero</a>
                    <a href="{{ route('admin.users.index') }}" class="py-2 text-sm font-medium">Usuarios</a>
                    <a href="{{ route('admin.courses.index') }}" class="py-2 text-sm font-medium">Cursos</a>
                    <a href="{{ route('admin.events.index') }}" class="py-2 text-sm font-medium">Eventos</a>
                    <a href="{{ route('admin.notifications.index') }}" class="py-2 text-sm font-medium">Notificaciones</a>
                </div>
            </div>
            
            <!-- Lado derecho con configuración -->
            <div class="relative flex items-center">
                <div x-data="{ open: false }" class="relative">
                    <button 
                        @click="open = !open" 
                        @click.away="open = false"
                        class="flex items-center focus:outline-none"
                    >
                        <div class="relative h-10 w-10">
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-white" 
                                 src="{{ Auth::user()->photo_url }}" 
                                 alt="{{ Auth::user()->name }}"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}'">
                            <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 border-2 border-blue-600"></span>
                        </div>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-100" 
                        x-transition:enter-start="transform opacity-0 scale-95" 
                        x-transition:enter-end="transform opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-75" 
                        x-transition:leave-start="transform opacity-100 scale-100" 
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-xl overflow-visible z-50 border border-gray-200"
                        style="display: none;"
                        @click.away="open = false"
                    >
                        <div class="py-1">
                            <!-- Administrar Usuarios -->
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center px-4 py-3 text-base font-semibold text-gray-800 hover:bg-blue-50 transition-colors duration-200">
                                <i class="fas fa-users-cog mr-3 text-blue-600 w-5 text-center"></i>
                                <span>Administrar Usuarios</span>
                            </a>
                            
                            <!-- Mi Perfil -->
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="{{ route('admin.profile.edit') }}" 
                               class="flex items-center px-4 py-3 text-base font-semibold text-gray-800 hover:bg-blue-50 transition-colors duration-200">
                                <i class="fas fa-user-edit mr-3 text-blue-600 w-5 text-center"></i>
                                <span>Mi Perfil</span>
                            </a>
                            
                            <!-- Cerrar Sesión -->
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center px-4 py-3 text-base font-semibold text-red-600 hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <div class="flex-1 p-6 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <!-- Grid principal -->
                <div class="grid grid-cols-12 gap-6">
                    <!-- Columna izquierda: Perfil y enlaces -->
                    <div class="col-span-12 md:col-span-3 space-y-6">
                        <!-- Tarjeta de perfil -->
                        <div class="card p-6">
                            <div class="flex flex-col items-center">
                                <div class="h-24 w-24 rounded-full overflow-hidden bg-gray-100 mb-4 border-4 border-white shadow">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff&size=150" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                </div>
                                
                                <h2 class="text-lg font-semibold text-center">{{ auth()->user()->name }}</h2>
                                <p class="text-sm text-gray-500 text-center mb-2">{{ auth()->user()->email }}</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-4">
                                    Administrador
                                </span>
                                
                                <div class="w-full border-t border-gray-200 pt-4 mt-2">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-500">Tiempo en Puesto</span>
                                        <span class="text-sm font-medium">{{ auth()->user()->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Créditos</span>
                                        <span class="text-sm font-medium">{{ rand(100, 999) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Acciones principales -->
                        <div class="card p-4">
                            <h3 class="text-base font-medium mb-3">Acciones Rápidas</h3>
                            <div class="space-y-2">
                                <a href="{{ route('admin.users.create') }}" class="action-card">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600 mr-3">
                                        <i class="fas fa-user-plus"></i>
                                    </span>
                                    <span class="text-sm font-medium">Nuevo Usuario</span>
                                    <span class="ml-auto bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">3</span>
                                </a>
                                
                                <a href="{{ route('admin.courses.create') }}" class="action-card">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100 text-green-600 mr-3">
                                        <i class="fas fa-book"></i>
                                    </span>
                                    <span class="text-sm font-medium">Nuevo Curso</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Columna central: Actividad Diaria y estadísticas -->
                    <div class="col-span-12 md:col-span-6 space-y-6">
                        <!-- Título de la sección -->
                        <div>
                            <h2 class="text-xl font-semibold">Actividad Diaria</h2>
                            <p class="text-sm text-gray-500">Resumen de la actividad reciente en la plataforma</p>
                        </div>
                        
                        <!-- Tarjetas de actividad principal con nuevo diseño -->
                        <div class="card p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base font-medium">Resumen de Actividades</h3>
                                <button class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center">
                                    <span>Ver todos</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="space-y-5">
                                <!-- Tarjetas de actividad con estilo moderno -->
                                <div class="action-card border-blue-500 hover:bg-blue-50 transition-all duration-300">
                                    <div class="flex items-center flex-1">
                                        <div class="bg-blue-100 p-3 rounded-xl mr-4 shadow-sm">
                                            <i class="fas fa-user-graduate text-blue-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Últimos Registros</p>
                                            <p class="text-sm text-gray-500">Nuevos estudiantes esta semana</p>
                                        </div>
                                    </div>
                                    <span class="text-2xl font-bold text-blue-700">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'estudiante'); })->whereDate('created_at', '>=', now()->subWeek())->count() }}</span>
                                </div>
                                
                                <div class="action-card border-green-500 hover:bg-green-50 transition-all duration-300">
                                    <div class="flex items-center flex-1">
                                        <div class="bg-green-100 p-3 rounded-xl mr-4 shadow-sm">
                                            <i class="fas fa-book text-green-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Inscripciones a Cursos</p>
                                            <p class="text-sm text-gray-500">Nuevas inscripciones este mes</p>
                                        </div>
                                    </div>
                                    <span class="text-2xl font-bold text-green-700">{{ rand(15, 50) }}</span>
                                </div>
                                
                                <div class="action-card border-yellow-500 hover:bg-yellow-50 transition-all duration-300">
                                    <div class="flex items-center flex-1">
                                        <div class="bg-yellow-100 p-3 rounded-xl mr-4 shadow-sm">
                                            <i class="fas fa-calendar-check text-yellow-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Eventos Próximos</p>
                                            <p class="text-sm text-gray-500">En los próximos 7 días</p>
                                        </div>
                                    </div>
                                    @php
                                        $eventCount = \App\Models\Event::where('start_date', '>=', now())
                                            ->where('start_date', '<=', now()->addDays(7))
                                            ->count();
                                    @endphp
                                    <span class="text-2xl font-bold text-yellow-700">{{ $eventCount }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjeta de estadísticas con barras de progreso modernas -->
                        <div class="card p-5">
                            <h3 class="text-base font-medium mb-4">Estadísticas Globales</h3>
                            
                            <div class="flex flex-col space-y-5">
                                <!-- Progreso de usuarios activos -->
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <p class="text-sm font-medium flex items-center">
                                            <span class="w-3 h-3 inline-block bg-blue-500 rounded-full mr-2"></span>
                                            Usuarios Activos
                                        </p>
                                        <p class="text-sm font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full">79%</p>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-value" style="width: 79%"></div>
                                    </div>
                                </div>
                                
                                <!-- Progreso de cursos completados -->
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <p class="text-sm font-medium flex items-center">
                                            <span class="w-3 h-3 inline-block bg-green-500 rounded-full mr-2"></span>
                                            Cursos Completados
                                        </p>
                                        <p class="text-sm font-medium bg-green-100 text-green-800 px-2 py-1 rounded-full">65%</p>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-value bg-gradient-to-r from-green-500 to-green-400" style="width: 65%"></div>
                                    </div>
                                </div>
                                
                                <!-- Progreso de satisfacción -->
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <p class="text-sm font-medium flex items-center">
                                            <span class="w-3 h-3 inline-block bg-purple-500 rounded-full mr-2"></span>
                                            Satisfacción
                                        </p>
                                        <p class="text-sm font-medium bg-purple-100 text-purple-800 px-2 py-1 rounded-full">92%</p>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-value bg-gradient-to-r from-purple-500 to-indigo-500" style="width: 92%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Columna derecha: Gráficos y notificaciones -->
                    <div class="col-span-12 md:col-span-3 space-y-6">
                        <!-- Tarjeta de Gráfico Circular con estilo moderno -->
                        <div class="card p-5">
                            <h3 class="text-base font-medium mb-3">Rendimiento Global</h3>
                            
                            <div class="flex flex-col items-center">
                                @php
                                    // Obtener métricas reales (ejemplo con datos estáticos por ahora)
                                    $performancePercentage = 79; // Porcentaje de rendimiento general
                                    $activityIncrease = 12; // % de aumento en actividad
                                    $retentionIncrease = 8; // % de aumento en retención
                                    
                                    // Calcular el offset para el círculo de progreso (339.5 es la circunferencia aproximada)
                                    $circumference = 339.5;
                                    $offset = $circumference - ($performancePercentage / 100 * $circumference);
                                @endphp
                                
                                <!-- SVG Circle Progress -->
                                <div class="relative w-32 h-32 mb-4">
                                    <svg class="w-full h-full" viewBox="0 0 120 120">
                                        <!-- Círculo de fondo -->
                                        <circle class="text-gray-200" cx="60" cy="60" r="54" fill="none" stroke="currentColor" stroke-width="12"></circle>
                                        <!-- Círculo de progreso -->
                                        <circle class="text-blue-600 transform -rotate-90 origin-center" 
                                                cx="60" cy="60" r="54" 
                                                fill="none" 
                                                stroke="currentColor" 
                                                stroke-width="12"
                                                stroke-dasharray="{{ $circumference }}"
                                                stroke-dashoffset="{{ $offset }}"
                                                stroke-linecap="round">
                                        </circle>
                                        <!-- Porcentaje -->
                                        <text class="text-2xl font-bold fill-current text-gray-800" x="60" y="65" text-anchor="middle" dominant-baseline="middle">{{ $performancePercentage }}%</text>
                                    </svg>
                                </div>
                                
                                <div class="text-center mb-4">
                                    <h4 class="font-medium text-gray-800">Rendimiento General</h4>
                                    <p class="text-sm text-gray-500">Mejora con respecto al mes anterior</p>
                                </div>
                                
                                <div class="w-full grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-3 rounded-lg text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <p class="text-sm font-medium text-gray-600">Actividad</p>
                                        <p class="text-lg font-bold text-blue-600">+{{ $activityIncrease }}%</p>
                                        <div class="h-1 w-full bg-blue-100 mt-2 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500" style="width: {{ min($activityIncrease, 100) }}%"></div>
                                        </div>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <p class="text-sm font-medium text-gray-600">Retención</p>
                                        <p class="text-lg font-bold text-green-600">+{{ $retentionIncrease }}%</p>
                                        <div class="h-1 w-full bg-green-100 mt-2 rounded-full overflow-hidden">
                                            <div class="h-full bg-green-500" style="width: {{ min($retentionIncrease, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Próximos eventos con estilo moderno -->
                        <div class="card p-5">
                            <h3 class="text-base font-medium mb-3">Próximos Eventos</h3>
                            
                            <div class="space-y-3">
                                @for ($i = 1; $i <= 3; $i++)
                                <div class="action-card border-transparent hover:border-blue-500 flex items-center py-2 px-3">
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex flex-col items-center justify-center shadow-sm">
                                            <span class="text-xs font-bold text-blue-600">{{ now()->addDays($i)->format('d') }}</span>
                                            <span class="text-xs text-blue-600">{{ now()->addDays($i)->format('M') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">{{ ['Reunión Mensual', 'Taller de Formación', 'Revisión de Cursos'][$i-1] }}</p>
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-clock mr-1 text-blue-500"></i>
                                            {{ now()->addDays($i)->format('H:i') }} - 
                                            <i class="fas fa-map-marker-alt mx-1 text-blue-500"></i>
                                            {{ ['Online', 'Sala 3', 'Auditorio'][$i-1] }}
                                        </p>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alpine.js y scripts necesarios -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Script para las funcionalidades interactivas -->
    <script>
        document.addEventListener('alpine:init', () => {
            // Aquí puedes inicializar cualquier dato global de Alpine si es necesario
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle para menú móvil (si se implementa)
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    // Aquí iría la lógica para mostrar/ocultar menú móvil
                });
            }
            
            // Ocultar alerta de error después de 5 segundos
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.opacity = '0';
                    errorAlert.style.transition = 'opacity 0.5s';
                    setTimeout(function() {
                        errorAlert.remove();
                    }, 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>
