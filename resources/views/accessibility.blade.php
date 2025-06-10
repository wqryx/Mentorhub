<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declaración de Accesibilidad - MentorHub</title>
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
        .section {
            scroll-margin-top: 1.5rem;
        }
        .accessibility-feature {
            @apply bg-gray-50 p-4 rounded-lg mb-4;
        }
        .compliance-status {
            @apply p-4 rounded-lg mb-6 border-l-4;
        }
        .compliant {
            @apply border-green-500 bg-green-50;
        }
        .partially-compliant {
            @apply border-yellow-500 bg-yellow-50;
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">
        <div class="text-center mb-10">
            <a href="/" class="inline-block mb-6">
                <span class="text-2xl font-bold text-indigo-600">MentorHub</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Declaración de Accesibilidad</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
            <div class="space-y-8">
                <div class="compliance-status compliant">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Estado de cumplimiento</h2>
                    <p class="text-gray-700">
                        MentorHub está comprometido con hacer que su sitio web sea accesible para todas las personas, incluidas aquellas con discapacidad. Nos esforzamos por cumplir con las Pautas de Accesibilidad para el Contenido Web (WCAG) 2.1 Nivel AA.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">1. Medidas de accesibilidad adoptadas</h2>
                    <p class="text-gray-600 mb-4">
                        Para garantizar la accesibilidad de nuestro sitio web, hemos implementado las siguientes medidas:
                    </p>
                    
                    <div class="accessibility-feature">
                        <h3 class="font-semibold text-gray-800 mb-2">1.1 Navegación con teclado</h3>
                        <p class="text-gray-600">
                            Todo el contenido es navegable mediante teclado, permitiendo a los usuarios moverse por la página usando la tecla Tab y otras combinaciones de teclas.
                        </p>
                    </div>

                    <div class="accessibility-feature">
                        <h3 class="font-semibold text-gray-800 mb-2">1.2 Contraste de colores</h3>
                        <p class="text-gray-600">
                            Hemos asegurado que el contraste entre el texto y el fondo cumpla con los estándares WCAG 2.1 Nivel AA.
                        </p>
                    </div>

                    <div class="accessibility-feature">
                        <h3 class="font-semibold text-gray-800 mb-2">1.3 Estructura semántica</h3>
                        <p class="text-gray-600">
                            Utilizamos encabezados (h1, h2, h3, etc.) de manera jerárquica para organizar el contenido de manera lógica.
                        </p>
                    </div>

                    <div class="accessibility-feature">
                        <h3 class="font-semibold text-gray-800 mb-2">1.4 Texto alternativo</h3>
                        <p class="text-gray-600">
                            Todas las imágenes relevantes incluyen texto alternativo descriptivo.
                        </p>
                    </div>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">2. Limitaciones y alternativas</h2>
                    <p class="text-gray-600 mb-4">
                        A pesar de nuestros esfuerzos por garantizar la accesibilidad de MentorHub, pueden existir algunas limitaciones. A continuación, se detallan las áreas conocidas y las alternativas disponibles:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 mb-4">
                        <li><strong>Contenido de terceros:</strong> Algunos elementos incrustados de terceros pueden no ser completamente accesibles.</li>
                        <li><strong>Documentos descargables:</strong> Algunos documentos en formato PDF pueden no estar totalmente accesibles.</li>
                        <li><strong>Contenido multimedia:</strong> Algunos videos pueden carecer de subtítulos o transcripciones completas.</li>
                    </ul>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">3. Tecnologías de asistencia y navegadores</h2>
                    <p class="text-gray-600 mb-4">
                        Hemos probado la accesibilidad de nuestro sitio con las siguientes tecnologías de asistencia y navegadores:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 mb-4">
                        <li>Lector de pantalla NVDA con Firefox</li>
                        <li>VoiceOver con Safari</li>
                        <li>Navegación por teclado en Chrome, Firefox, Safari y Edge</li>
                        <li>Zoom del navegador hasta 200%</li>
                    </ul>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">4. Comentarios y contacto</h2>
                    <p class="text-gray-600 mb-4">
                        Valoramos sus comentarios sobre la accesibilidad de MentorHub. Si experimenta alguna dificultad para acceder a nuestro sitio o tiene sugerencias para mejorar la accesibilidad, por favor contáctenos:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 mb-4">
                        <li>Correo electrónico: accesibilidad@mentorhub.com</li>
                        <li>Teléfono: +34 123 456 789</li>
                        <li>Formulario de contacto: <a href="/contacto" class="text-indigo-600 hover:underline">Página de contacto</a></li>
                    </ul>
                    <p class="text-gray-600">
                        Nos esforzamos por responder a los comentarios sobre accesibilidad en un plazo de 5 días hábiles.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">5. Proceso de cumplimiento</h2>
                    <p class="text-gray-600 mb-4">
                        Estamos comprometidos a mantener y mejorar continuamente la accesibilidad de nuestro sitio. Nuestro proceso incluye:
                    </p>
                    <ol class="list-decimal pl-5 space-y-2 text-gray-600 mb-4">
                        <li>Evaluaciones periódicas de accesibilidad</li>
                        <li>Formación del personal en accesibilidad web</li>
                        <li>Actualización de contenido para cumplir con los estándares</li>
                        <li>Revisión de comentarios de los usuarios</li>
                    </ol>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-gray-100 text-center">
                <a href="/" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Volver al inicio
                </a>
            </div>
        </div>

        <div class="mt-8 text-center text-sm text-gray-500">
            <p>© {{ date('Y') }} MentorHub. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
