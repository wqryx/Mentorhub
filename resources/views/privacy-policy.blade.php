<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - MentorHub</title>
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
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">
        <div class="text-center mb-10">
            <a href="/" class="inline-block mb-6">
                <span class="text-2xl font-bold text-indigo-600">MentorHub</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Política de Privacidad</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
            <div class="space-y-8">
                <div>
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">Información que Recopilamos</h2>
                    <p class="text-gray-600">
                        Recopilamos información personal que nos proporcionas voluntariamente, como tu nombre, correo electrónico y datos de perfil cuando te registras en nuestra plataforma o interactúas con nuestros servicios.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">Uso de la Información</h2>
                    <p class="text-gray-600 mb-2">
                        Utilizamos tu información para los siguientes fines:
                    </p>
                    <ul class="list-disc pl-5 space-y-1 text-gray-600">
                        <li>Proporcionar y mantener nuestros servicios</li>
                        <li>Mejorar la experiencia del usuario</li>
                        <li>Comunicarnos contigo sobre actualizaciones y noticias</li>
                        <li>Personalizar tu experiencia en la plataforma</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">Seguridad de la Información</h2>
                    <p class="text-gray-600">
                        Implementamos medidas de seguridad técnicas y organizativas para proteger tu información personal contra accesos no autorizados, alteración, divulgación o destrucción no autorizada de los datos que almacenamos.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">Tus Derechos</h2>
                    <p class="text-gray-600">
                        Tienes derecho a acceder, corregir o eliminar tu información personal en cualquier momento. Si deseas ejercer estos derechos, por favor contáctanos a través de nuestro formulario de contacto.
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
