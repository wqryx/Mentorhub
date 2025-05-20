<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Roles - MentorHub Admin</title>
    <meta name="description" content="Gestión de roles para MentorHub">
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
                                <a href="{{ route('admin.logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Gestión de Roles</h2>
                        <a href="{{ route('admin.roles.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Crear Nuevo Rol
                        </a>
                    </div>

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuarios</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($roles as $role)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $role->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $role->description ?? 'Sin descripción' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $role->users()->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.roles.show', $role->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                            Ver
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-green-600 hover:text-green-900 mr-2">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de eliminar este rol?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
