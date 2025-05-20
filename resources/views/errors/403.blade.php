<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error 403 - Acceso Denegado</title>
    <meta name="description" content="Error 403 - No tienes permisos para acceder a esta página">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-red-600">403</h1>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Acceso Denegado</h2>
                <p class="mt-2 text-gray-600">No tienes permisos para acceder a esta página.</p>
            </div>
            
            <div class="mt-8">
                <a href="{{ route('admin.login') }}" 
                   class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </div>
</body>
</html>
