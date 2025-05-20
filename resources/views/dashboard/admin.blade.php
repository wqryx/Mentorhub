<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Administrador - MentorHub</title>
    <meta name="description" content="Dashboard de administrador para MentorHub">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                                <span class="text-xl font-bold text-gray-800">MentorHub Admin</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="ml-3 relative">
                            <div>
                                <button type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Abrir menú de usuario</span>
                                    <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700">
                                        {{ auth()->user()->name }}
                                    </span>
                                </button>
                            </div>
                            
                            <!-- Dropdown menu -->
                            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Perfil
                                </a>
                                <form action="{{ route('admin.logout') }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    @csrf
                                    <button type="submit" class="w-full text-left">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Dashboard Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <!-- Total Users -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Usuarios</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                            </div>
                            <div class="text-blue-500">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>

                    <!-- New Users -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Nuevos Usuarios</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::whereDate('created_at', today())->count() }}</p>
                            </div>
                            <div class="text-green-500">
                                <i class="fas fa-user-plus fa-2x"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Active Sessions -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Sesiones Activas</p>
                                <p class="text-2xl font-bold text-gray-900">1</p>
                            </div>
                            <div class="text-purple-500">
                                <i class="fas fa-sign-in-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Requests -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Solicitudes Pendientes</p>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                            <div class="text-yellow-500">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- User Management -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Gestión de Usuarios</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Total Usuarios</span>
                                <a href="{{ route('admin.users.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Ver Todos
                                </a>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Crear Nuevo Usuario</span>
                                <a href="{{ route('admin.users.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Crear
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Role Management -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Gestión de Roles</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Roles Activos</span>
                                <a href="{{ route('admin.roles.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Ver Todos
                                </a>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Crear Nuevo Rol</span>
                                <a href="{{ route('admin.roles.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Crear
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="bg-white p-6 rounded-lg shadow col-span-2">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Registro de Actividad</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Últimas Actividades</span>
                                <a href="{{ route('admin.activities.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Ver Todo
                                </a>
                            </div>
                            <div class="space-y-2">
                                <!-- Last activities will be shown here -->
                                <div class="flex items-center justify-between text-gray-600">
                                    <span>No hay actividades registradas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
