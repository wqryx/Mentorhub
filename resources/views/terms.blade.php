<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - MentorHub</title>
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
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">
        <div class="text-center mb-10">
            <a href="/" class="inline-block mb-6">
                <span class="text-2xl font-bold text-indigo-600">MentorHub</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Términos y Condiciones de Uso</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
            <div class="space-y-8">
                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">1. Aceptación de los Términos</h2>
                    <p class="text-gray-600 mb-4">
                        Al acceder y utilizar MentorHub, aceptas cumplir con estos términos y condiciones. Si no estás de acuerdo con alguna parte de estos términos, por favor no utilices nuestro servicio.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">2. Uso del Servicio</h2>
                    <p class="text-gray-600 mb-4">
                        MentorHub es una plataforma educativa que conecta a estudiantes con mentores. Al utilizar nuestro servicio, te comprometes a:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600 mb-4">
                        <li>Proporcionar información precisa y actualizada</li>
                        <li>Mantener la confidencialidad de tu cuenta</li>
                        <li>No utilizar la plataforma con fines ilegales o no autorizados</li>
                        <li>Respetar los derechos de propiedad intelectual</li>
                    </ul>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">3. Cuentas de Usuario</h2>
                    <p class="text-gray-600 mb-4">
                        Para acceder a ciertas funcionalidades, deberás crear una cuenta. Eres responsable de mantener la confidencialidad de tus credenciales y de todas las actividades que ocurran bajo tu cuenta.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">4. Contenido del Usuario</h2>
                    <p class="text-gray-600 mb-4">
                        Eres el único responsable del contenido que publiques en la plataforma. Al publicar contenido, nos otorgas una licencia no exclusiva para mostrarlo y distribuirlo en nuestra plataforma.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">5. Cancelación y Suspensión</h2>
                    <p class="text-gray-600 mb-4">
                        Nos reservamos el derecho de suspender o cancelar tu cuenta si violas estos términos o realizas actividades que consideremos inapropiadas o perjudiciales para la comunidad.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">6. Limitación de Responsabilidad</h2>
                    <p class="text-gray-600 mb-4">
                        MentorHub no se hace responsable por daños indirectos, incidentales o consecuentes que puedan surgir del uso o la imposibilidad de uso de nuestros servicios.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">7. Modificaciones</h2>
                    <p class="text-gray-600 mb-4">
                        Podemos modificar estos términos en cualquier momento. Te notificaremos sobre cambios significativos a través de tu correo electrónico o mediante un aviso en nuestra plataforma.
                    </p>
                </div>

                <div class="section">
                    <h2 class="text-xl font-semibold mb-3 text-gray-800">8. Contacto</h2>
                    <p class="text-gray-600">
                        Si tienes alguna pregunta sobre estos términos, por favor contáctanos a través de nuestro formulario de contacto.
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
