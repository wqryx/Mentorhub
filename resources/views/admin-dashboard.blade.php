<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MentorHub - Dashboard Administrador</title>
    <meta name="description" content="Panel de administración de MentorHub">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/guest-dashboard.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js"></script>
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
                    <h3 class="font-semibold">Administrador</h3>
                    <p class="text-sm text-gray-600">Super Usuario</p>
                </div>
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
                            <i class="fas fa-user-plus mr-3"></i> Solicitudes
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-users mr-3"></i> Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-book mr-3"></i> Cursos
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-tasks mr-3"></i> Actividades
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-chart-bar mr-3"></i> Estadísticas
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Panel de Administración</h1>
            <div class="flex items-center space-x-4">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-bell mr-2"></i> Notificaciones (3)
                </button>
                <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                    <i class="fas fa-moon mr-2"></i> Modo Oscuro
                </button>
            </div>
        </header>

        <!-- Contenido Principal -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Solicitudes Pendientes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Solicitudes Pendientes</h2>
                <div class="space-y-4">
                    <!-- Solicitud de Estudiante -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <h3 class="font-medium">Solicitud de Estudiante</h3>
                                <p class="text-sm text-gray-600">Juan Pérez</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                    <i class="fas fa-check mr-2"></i> Aceptar
                                </button>
                                <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                    <i class="fas fa-times mr-2"></i> Rechazar
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">FP Grado Superior en DAW</p>
                            <p class="text-sm text-gray-600">Email: juan.perez@example.com</p>
                        </div>
                    </div>

                    <!-- Solicitud de Mentor -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <h3 class="font-medium">Solicitud de Mentor</h3>
                                <p class="text-sm text-gray-600">María García</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                    <i class="fas fa-check mr-2"></i> Aceptar
                                </button>
                                <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                    <i class="fas fa-times mr-2"></i> Rechazar
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">Especialidad: Desarrollo Web</p>
                            <p class="text-sm text-gray-600">Email: maria.garcia@example.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Estadísticas</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800">Usuarios Totales</h3>
                        <p class="text-3xl font-bold">125</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800">Estudiantes</h3>
                        <p class="text-3xl font-bold">85</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-yellow-800">Mentores</h3>
                        <p class="text-3xl font-bold">30</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-purple-800">Solicitudes Pendientes</h3>
                        <p class="text-3xl font-bold">5</p>
                    </div>
                </div>
            </div>

            <!-- Últimas Actividades -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Últimas Actividades</h2>
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <i class="fas fa-user-plus text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Nueva solicitud de estudiante</h3>
                            <p class="text-sm text-gray-600">Juan Pérez ha solicitado registro</p>
                            <p class="text-xs text-gray-500">Hace 2 horas</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4">
                            <i class="fas fa-user-check text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Usuario aceptado</h3>
                            <p class="text-sm text-gray-600">María García ha sido aceptada como mentor</p>
                            <p class="text-xs text-gray-500">Hace 3 horas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Usuarios -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Gestión de Usuarios</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium">Estudiantes Activos</h3>
                            <p class="text-sm text-gray-600">85 estudiantes</p>
                        </div>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-eye mr-2"></i> Ver Lista
                        </button>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium">Mentores Activos</h3>
                            <p class="text-sm text-gray-600">30 mentores</p>
                        </div>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-eye mr-2"></i> Ver Lista
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-question-circle"></i>
                </button>
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-file-contract"></i>
                </button>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-comments"></i>
                </button>
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-globe"></i>
                </button>
            </div>
        </div>
    </footer>
</body>
</html>
