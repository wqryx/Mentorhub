<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - MentorHub</title>
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
        }
        .event-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .event-card {
            transition: all 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6">
    <div class="container mx-auto">
        <!-- Encabezado -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Próximos Eventos</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Participa en nuestros eventos en vivo, talleres y sesiones de networking para potenciar tu aprendizaje.
            </p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Filtrar Eventos</h2>
            <div class="flex flex-wrap gap-4">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                    Todos
                </button>
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Webinars
                </button>
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Talleres
                </button>
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Conferencias
                </button>
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Networking
                </button>
            </div>
        </div>

        <!-- Evento Destacado -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 event-card">
            <div class="md:flex">
                <div class="md:w-1/3 bg-gradient-to-br from-indigo-600 to-purple-600 p-8 flex items-center justify-center">
                    <div class="text-center text-white">
                        <div class="text-5xl font-bold">15</div>
                        <div class="text-2xl font-medium">JUN</div>
                        <div class="mt-2 text-indigo-100">18:00 - 20:00</div>
                        <div class="mt-4">
                            <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">Destacado</span>
                        </div>
                    </div>
                </div>
                <div class="p-8 md:w-2/3">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="event-category bg-indigo-100 text-indigo-800">Conferencia</span>
                            <span class="ml-2 text-sm text-gray-500">Presencial</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Centro de Convenciones
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">Conferencia Anual de Mentoría 2025</h2>
                    <p class="text-gray-600 mb-4">
                        Únete a nuestra conferencia anual donde expertos de la industria compartirán sus conocimientos sobre mentoría, liderazgo y desarrollo profesional. Aprende de los mejores y amplía tu red de contactos.
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex -space-x-2">
                                <img class="h-8 w-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/44.jpg" alt="">
                                <img class="h-8 w-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/32.jpg" alt="">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-xs font-medium text-indigo-800">+12</div>
                            </div>
                            <span class="ml-2 text-sm text-gray-500">Asistentes confirmados</span>
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Próximos Eventos -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Más Eventos Próximos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Evento 1 -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden event-card">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">Webinar</div>
                            <div class="text-sm text-gray-500">20 JUN</div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Mentoría en la Era Digital</h3>
                        <p class="text-gray-600 text-sm mb-4">Aprende a aprovechar las herramientas digitales para mejorar tus sesiones de mentoría.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                16:00 - 17:30
                            </div>
                            <span class="text-sm text-indigo-600 font-medium">En línea</span>
                        </div>
                    </div>
                </div>

                <!-- Evento 2 -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden event-card">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">Taller</div>
                            <div class="text-sm text-gray-500">22 JUN</div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Habilidades de Comunicación</h3>
                        <p class="text-gray-600 text-sm mb-4">Mejora tus habilidades de comunicación para ser un mejor mentor o aprendiz.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                10:00 - 13:00
                            </div>
                            <span class="text-sm text-indigo-600 font-medium">Presencial</span>
                        </div>
                    </div>
                </div>

                <!-- Evento 3 -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden event-card">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">Networking</div>
                            <div class="text-sm text-gray-500">25 JUN</div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Encuentro de Mentores</h3>
                        <p class="text-gray-600 text-sm mb-4">Conecta con otros mentores y comparte experiencias en un ambiente relajado.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                18:30 - 20:30
                            </div>
                            <span class="text-sm text-indigo-600 font-medium">Híbrido</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eventos Pasados -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Eventos Anteriores</h2>
            <div class="space-y-4">
                <!-- Evento Pasado 1 -->
                <div class="bg-white rounded-xl shadow-sm p-6 opacity-75">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="mb-4 md:mb-0 md:mr-6">
                            <h3 class="text-lg font-semibold text-gray-900">Taller de Liderazgo</h3>
                            <p class="text-gray-600 text-sm">5 de Junio, 2025</p>
                        </div>
                        <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                            Ver grabación
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Evento Pasado 2 -->
                <div class="bg-white rounded-xl shadow-sm p-6 opacity-75">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="mb-4 md:mb-0 md:mr-6">
                            <h3 class="text-lg font-semibold text-gray-900">Introducción a la Mentoría</h3>
                            <p class="text-gray-600 text-sm">28 de Mayo, 2025</p>
                        </div>
                        <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                            Ver grabación
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
