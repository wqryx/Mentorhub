<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Cookies - MentorHub</title>
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
        .cookie-type {
            @apply bg-gray-50 p-4 rounded-lg mb-4;
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">
        <div class="text-center mb-10">
            <a href="/" class="inline-block mb-6">
                <span class="text-2xl font-bold text-indigo-600">MentorHub</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Política de Cookies</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
            <div class="space-y-8">
                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">1. ¿Qué son las cookies?</h2>
                    <p class="text-gray-600">
                        Las cookies son pequeños archivos de texto que los sitios web instalan en el ordenador o dispositivo móvil de los usuarios que los visitan. Son ampliamente utilizados para hacer que los sitios web funcionen de manera más eficiente, así como para proporcionar información a los propietarios del sitio.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">2. Tipos de cookies que utilizamos</h2>
                    
                    <div class="cookie-type">
                        <h3 class="font-semibold text-gray-800 mb-2">Cookies técnicas</h3>
                        <p class="text-gray-600">
                            Son necesarias para la navegación y el buen funcionamiento de nuestra página web. Permiten, por ejemplo, controlar el tráfico y la comunicación de datos, acceder a partes de acceso restringido, etc.
                        </p>
                    </div>

                    <div class="cookie-type">
                        <h3 class="font-semibold text-gray-800 mb-2">Cookies de personalización</h3>
                        <p class="text-gray-600">
                            Permiten al usuario acceder al servicio con algunas características de carácter general predefinidas en función de una serie de criterios en el terminal del usuario como, por ejemplo, el idioma, el tipo de navegador, etc.
                        </p>
                    </div>

                    <div class="cookie-type">
                        <h3 class="font-semibold text-gray-800 mb-2">Cookies de análisis</h3>
                        <p class="text-gray-600">
                            Nos permiten cuantificar el número de usuarios y así realizar la medición y análisis estadístico de la utilización que hacen los usuarios del servicio ofertado.
                        </p>
                    </div>

                    <div class="cookie-type">
                        <h3 class="font-semibold text-gray-800 mb-2">Cookies publicitarias</h3>
                        <p class="text-gray-600">
                            Permiten la gestión de los espacios publicitarios en nuestra web en base a criterios como el contenido mostrado o la frecuencia en la que se muestran los anuncios.
                        </p>
                    </div>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">3. Cómo gestionar las cookies</h2>
                    <p class="text-gray-600 mb-4">
                        Puedes permitir, bloquear o eliminar las cookies instaladas en tu equipo mediante la configuración de las opciones de tu navegador. A continuación, te indicamos cómo hacerlo en los principales navegadores:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 mb-4">
                        <li><a href="https://support.google.com/chrome/answer/95647?hl=es" target="_blank" class="text-indigo-600 hover:underline">Chrome</a></li>
                        <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web-rastrear-preferencias" target="_blank" class="text-indigo-600 hover:underline">Firefox</a></li>
                        <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" class="text-indigo-600 hover:underline">Safari</a></li>
                        <li><a href="https://support.microsoft.com/es-es/microsoft-edge/eliminar-las-cookies-en-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" class="text-indigo-600 hover:underline">Microsoft Edge</a></li>
                    </ul>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">4. Cambios en la política de cookies</h2>
                    <p class="text-gray-600">
                        Es posible que actualicemos la Política de Cookies de nuestro Sitio Web. Te notificaremos de cualquier cambio publicando la nueva Política de Cookies en esta página. Te recomendamos revisar esta Política de Cookies periódicamente para cualquier cambio.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">5. Contacto</h2>
                    <p class="text-gray-600">
                        Si tienes alguna pregunta sobre esta Política de Cookies, puedes contactar con nosotros a través de nuestro formulario de contacto.
                    </p>
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
