<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoriales - MentorHub</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .tutorial-card {
            transition: all 0.3s ease;
        }
        .tutorial-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .section {
            scroll-margin-top: 1.5rem;
        }
    </style>
</head>
<body class="min-h-screen">
<div class="py-12">
    <div class="container mx-auto">
        <!-- Encabezado -->
        <div class="text-center mb-10">
            <a href="/" class="inline-block mb-6">
                <span class="text-2xl font-bold text-indigo-600">MentorHub</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tutoriales</h1>
            <p class="text-gray-600">Aprende nuevas habilidades con nuestros tutoriales paso a paso</p>
        </div>

        <!-- Contenido principal -->
        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
            <!-- Barra de búsqueda -->
            <div class="mb-10">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" placeholder="Buscar tutoriales..." 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <select class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option>Todos los niveles</option>
                            <option>Principiante</option>
                            <option>Intermedio</option>
                            <option>Avanzado</option>
                        </select>
                        <select class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option>Todas las categorías</option>
                            <option>Desarrollo Web</option>
                            <option>Diseño UX/UI</option>
                            <option>Data Science</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Lista de tutoriales -->
            <div class="section">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Tutoriales destacados</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Tutorial 1 -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden tutorial-card">
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Principiante</span>
                                <span class="text-sm text-gray-500 ml-auto">25 min</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Introducción a Laravel</h3>
                            <p class="text-gray-600 text-sm mb-4">Aprende los conceptos básicos de Laravel creando una aplicación web completa desde cero.</p>
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full mr-2" src="https://randomuser.me/api/portraits/men/32.jpg" alt="Instructor">
                                <span class="text-sm text-gray-600">Juan Pérez</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tutorial 2 -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden tutorial-card">
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Intermedio</span>
                                <span class="text-sm text-gray-500 ml-auto">42 min</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Diseño UX/UI con Figma</h3>
                            <p class="text-gray-600 text-sm mb-4">Domina las herramientas esenciales de Figma para crear diseños profesionales.</p>
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full mr-2" src="https://randomuser.me/api/portraits/women/44.jpg" alt="Instructora">
                                <span class="text-sm text-gray-600">Ana López</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tutorial 3 -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden tutorial-card">
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Avanzado</span>
                                <span class="text-sm text-gray-500 ml-auto">1h 15min</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Machine Learning con Python</h3>
                            <p class="text-gray-600 text-sm mb-4">Introducción práctica al machine learning usando Python y scikit-learn.</p>
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full mr-2" src="https://randomuser.me/api/portraits/men/76.jpg" alt="Instructor">
                                <span class="text-sm text-gray-600">Carlos Ruiz</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de categorías -->
            <div class="section mt-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Explora por categoría</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="#" class="bg-white p-6 rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4m6-8l-4 4-4-4" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900">Desarrollo Web</h3>
                        <p class="text-sm text-gray-500">42 tutoriales</p>
                    </a>
                    
                    <a href="#" class="bg-white p-6 rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900">Diseño UX/UI</h3>
                        <p class="text-sm text-gray-500">35 tutoriales</p>
                    </a>
                    
                    <a href="#" class="bg-white p-6 rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900">Data Science</h3>
                        <p class="text-sm text-gray-500">28 tutoriales</p>
                    </a>
                    
                    <a href="#" class="bg-white p-6 rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900">Marketing Digital</h3>
                        <p class="text-sm text-gray-500">31 tutoriales</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 pt-6 border-t border-gray-100 text-center">
            <a href="/" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver al inicio
            </a>
        </div>

        <div class="mt-8 text-center text-sm text-gray-500">
            <p>© {{ date('Y') }} MentorHub. Todos los derechos reservados.</p>
        </div>
    </div>
</div>
</body>
</html>
