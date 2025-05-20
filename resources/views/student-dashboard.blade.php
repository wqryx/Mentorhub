<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MentorHub - Dashboard Estudiante</title>
    <meta name="description" content="Dashboard personal para estudiantes de MentorHub">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/guest-dashboard.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg">
        <div class="p-4">
            <!-- Logo -->
            <div class="flex items-center justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="MentorHub Logo" class="h-12">
            </div>

            <!-- Perfil -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="flex flex-col items-center mb-4">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Foto de perfil" class="w-24 h-24 rounded-full mb-2">
                    <h3 class="font-semibold">Juan Pérez</h3>
                    <p class="text-sm text-gray-600">FP Grado Superior en DAW</p>
                </div>
                <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 mb-2">
                    <i class="fas fa-user-edit mr-2"></i> Editar Perfil
                </button>
                <button class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300">
                    <i class="fas fa-cog mr-2"></i> Configuración
                </button>
            </div>

            <!-- Navegación -->
            <nav>
                <ul class="space-y-1">
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-home mr-3"></i> Inicio
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-book mr-3"></i> Mis Módulos
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-calendar-alt mr-3"></i> Calendario
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-tasks mr-3"></i> Tareas
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-chart-bar mr-3"></i> Progreso
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-envelope mr-3"></i> Mensajes
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-file mr-3"></i> Recursos
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-video mr-3"></i> Clases Grabadas
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-user-shield mr-3"></i> Tutor
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-briefcase mr-3"></i> Servicios
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</body>
</html>
