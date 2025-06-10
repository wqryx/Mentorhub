<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guías y Recursos - MentorHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .guide-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .difficulty-beginner { background-color: #dcfce7; color: #166534; }
        .difficulty-intermediate { background-color: #dbeafe; color: #1e40af; }
        .difficulty-advanced { background-color: #f3e8ff; color: #5b21b6; }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Guías y Recursos</h1>
            <p class="text-gray-600">
                Información útil para aprovechar al máximo MentorHub
            </p>
        </div>

        <div class="space-y-6">
            <!-- Guía 1 -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-3">
                    <span class="guide-category bg-indigo-100 text-indigo-800">Inicio Rápido</span>
                    <span class="text-sm text-gray-500">10 min</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Primeros Pasos</h2>
                <p class="text-gray-600 mb-4">Aprende a configurar tu perfil y navegar por la plataforma.</p>
                <div class="flex justify-between items-center">
                    <span class="guide-category difficulty-beginner">Principiante</span>
                </div>
            </div>

            <!-- Guía 2 -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-3">
                    <span class="guide-category bg-blue-100 text-blue-800">Sesiones</span>
                    <span class="text-sm text-gray-500">15 min</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Sesiones Exitosas</h2>
                <p class="text-gray-600 mb-4">Consejos para aprovechar al máximo tus sesiones de mentoría.</p>
                <div class="flex justify-between items-center">
                    <span class="guide-category difficulty-intermediate">Intermedio</span>
                </div>
            </div>

            <!-- Guía 3 -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-3">
                    <span class="guide-category bg-purple-100 text-purple-800">Recursos</span>
                    <span class="text-sm text-gray-500">20 min</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Herramientas</h2>
                <p class="text-gray-600 mb-4">Recursos recomendados para complementar tu aprendizaje.</p>
                <div class="flex justify-between items-center">
                    <span class="guide-category difficulty-beginner">Principiante</span>
                </div>
            </div>

            <!-- Guía 4 -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-3">
                    <span class="guide-category bg-green-100 text-green-800">Mentoría</span>
                    <span class="text-sm text-gray-500">25 min</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Para Mentores</h2>
                <p class="text-gray-600 mb-4">Estrategias para mejorar tus habilidades como mentor.</p>
                <div class="flex justify-between items-center">
                    <span class="guide-category difficulty-advanced">Avanzado</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
