<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidad - MentorHub</title>
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
        .category-tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Comunidad MentorHub</h1>
            <p class="text-gray-600">
                Conecta con otros miembros, comparte conocimientos y crece junto a nuestra comunidad.
            </p>
        </div>

        <div class="space-y-6">
            <!-- Foro -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start">
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Foro de Discusión</h2>
                        <p class="text-gray-600 mb-4">Participa en conversaciones, haz preguntas y comparte tus conocimientos con la comunidad.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="category-tag bg-blue-100 text-blue-800">General</span>
                            <span class="category-tag bg-green-100 text-green-800">Preguntas</span>
                            <span class="category-tag bg-purple-100 text-purple-800">Recursos</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eventos -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Próximos Eventos</h2>
                
                <div class="space-y-4">
                    <!-- Evento 1 -->
                    <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-start">
                            <div class="bg-indigo-100 text-indigo-800 rounded-lg p-3 text-center mr-4 min-w-[80px]">
                                <div class="text-sm font-medium">15 Jun</div>
                                <div class="text-2xl font-bold">18:00</div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Sesión de Networking</h3>
                                <p class="text-sm text-gray-600">Conecta con mentores y aprendices en nuestra sesión mensual de networking.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Evento 2 -->
                    <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-start">
                            <div class="bg-green-100 text-green-800 rounded-lg p-3 text-center mr-4 min-w-[80px]">
                                <div class="text-sm font-medium">22 Jun</div>
                                <div class="text-2xl font-bold">17:30</div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Taller de Habilidades Blandas</h3>
                                <p class="text-sm text-gray-600">Aprende técnicas para mejorar tu comunicación y liderazgo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recursos Compartidos -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Recursos Compartidos</h2>
                
                <div class="space-y-4">
                    <!-- Recurso 1 -->
                    <div class="flex items-start">
                        <div class="bg-gray-100 rounded-lg p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Guía para Mentores Principiantes</h3>
                            <p class="text-sm text-gray-600">Compartido por: María G. - 5 días atrás</p>
                        </div>
                    </div>
                    
                    <!-- Recurso 2 -->
                    <div class="flex items-start">
                        <div class="bg-gray-100 rounded-lg p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Webinar: Estrategias de Aprendizaje</h3>
                            <p class="text-sm text-gray-600">Compartido por: Carlos M. - 1 semana atrás</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
