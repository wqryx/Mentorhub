<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso de Invitado - MentorHub</title>
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
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">
        <div class="text-center mb-10">
            <a href="/" class="inline-block mb-6">
                <span class="text-2xl font-bold text-indigo-600">MentorHub</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Bienvenido a MentorHub</h1>
            <p class="text-gray-600">Explora nuestra plataforma como invitado</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
            <div class="space-y-8">
                <div class="text-center">
                    <p class="text-lg text-gray-600 mb-6">
                        Gracias por visitar MentorHub. Aquí tienes una vista previa de lo que ofrecemos:
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Característica 1 -->
                    <div class="feature-card bg-gray-50 p-6 rounded-lg">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Tutoriales y Cursos</h3>
                        <p class="text-gray-600">Accede a una amplia variedad de tutoriales y cursos sobre diferentes temas de programación y desarrollo.</p>
                    </div>

                    <!-- Característica 2 -->
                    <div class="feature-card bg-gray-50 p-6 rounded-lg">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Comunidad Activa</h3>
                        <p class="text-gray-600">Únete a nuestra comunidad de estudiantes y mentores apasionados por el aprendizaje.</p>
                    </div>

                    <!-- Característica 3 -->
                    <div class="feature-card bg-gray-50 p-6 rounded-lg">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Aprendizaje Personalizado</h3>
                        <p class="text-gray-600">Encuentra recursos adaptados a tu nivel y objetivos de aprendizaje.</p>
                    </div>

                    <!-- Característica 4 -->
                    <div class="feature-card bg-gray-50 p-6 rounded-lg">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">A tu ritmo</h3>
                        <p class="text-gray-600">Aprende cuando quieras y a tu propio ritmo, sin presiones ni horarios fijos.</p>
                    </div>
                </div>

                <div class="mt-10 text-center">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">¿Quieres acceder a todo el contenido?</h3>
                    <p class="text-gray-600 mb-6">Regístrate de forma gratuita para desbloquear todos los cursos y características de MentorHub.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors font-medium">
                            Crear cuenta gratuita
                        </a>
                        <a href="{{ route('login') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors font-medium">
                            Iniciar sesión
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-gray-100 text-center">
                <a href="/" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
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
