<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - MentorHub</title>
    <meta name="description" content="Dashboard de MentorHub">
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
                            <a href="{{ route('welcome') }}" class="flex items-center">
                                <span class="text-xl font-bold text-gray-800">MentorHub</span>
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
                                <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Bienvenido a MentorHub</h2>
                    <p class="text-gray-600 mb-4">¡Hola {{ auth()->user()->name }}! Como usuario invitado, puedes explorar algunas funcionalidades básicas de la plataforma.</p>
                    <div class="mt-6">
                        <a href="{{ route('chat.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-comments mr-2"></i>
                            Ir al Chat
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
