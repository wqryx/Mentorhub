<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a la Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center text-white">

    <div class="bg-white text-gray-800 rounded-lg shadow-lg p-10 max-w-xl w-full text-center">
        <h1 class="text-4xl font-bold mb-4">¡Bienvenido a la Plataforma!</h1>
        <p class="text-lg mb-8">Inicia sesión para acceder a tu cuenta.</p>

        <a href="{{ route('login') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded transition">
            Iniciar Sesión
        </a>

        {{-- Si en algún momento activas el registro público, descomenta esto --}}
        {{-- 
        <a href="{{ route('register') }}"
           class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded ml-4 transition">
            Registrarse
        </a>
        --}}
    </div>

</body>
</html>
