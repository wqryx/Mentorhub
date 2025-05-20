<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MentorHub - Campus Virtual</title>
    <meta name="description" content="Explora nuestro campus virtual educativo">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/guest-dashboard.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de tarjetas al cargar
            gsap.from('.feature-card', {
                duration: 0.8,
                opacity: 0,
                y: 20,
                stagger: 0.2,
                ease: 'power2.out'
            });

            // Animación de estadísticas
            gsap.from('.stats-card', {
                duration: 0.8,
                opacity: 0,
                x: -20,
                stagger: 0.2,
                ease: 'power2.out'
            });

            // Animación de secciones
            gsap.from('.section-content', {
                duration: 0.8,
                opacity: 0,
                y: 20,
                stagger: 0.2,
                ease: 'power2.out'
            });
        });
    </script>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-800">MentorHub</span>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">Inicio</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Registrarse</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Bienvenida -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Bienvenido al Campus Virtual</h1>
                <p class="text-gray-600">Explora todas las funcionalidades disponibles en nuestro campus educativo</p>
            </div>

            <!-- Sección de Funcionalidades -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Sala de Clases -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center mb-4">
                        <div class="feature-image mb-4 bg-blue-100 rounded-lg p-4">
                            <i class="fas fa-chalkboard-teacher text-4xl text-blue-600 mb-4"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Sala de Clases</h3>
                        <p class="text-gray-600 mb-4">Aulas virtuales interactivas:</p>
                        <ul class="list-disc list-inside text-gray-600">
                            <li>Videoconferencias en HD</li>
                            <li>Tableros interactivos</li>
                            <li>Compartición de pantalla</li>
                            <li>Grupos de trabajo</li>
                            <li>Chat en tiempo real</li>
                            <li>Registro de sesiones</li>
                        </ul>
                    </div>
                </div>

                <!-- Biblioteca Digital -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center mb-4">
                        <div class="feature-image mb-4 bg-green-100 rounded-lg p-4">
                            <i class="fas fa-book-reader text-4xl text-green-600 mb-4"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Biblioteca Digital</h3>
                        <p class="text-gray-600 mb-4">Recursos educativos ilimitados:</p>
                        <ul class="list-disc list-inside text-gray-600">
                            <li>E-books y revistas</li>
                            <li>Artículos científicos</li>
                            <li>Video tutoriales</li>
                            <li>Simulaciones interactivas</li>
                            <li>Base de datos de tesis</li>
                            <li>Material de investigación</li>
                        </ul>
                    </div>
                </div>

                <!-- Foro de Estudiantes -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center mb-4">
                        <div class="feature-image mb-4 bg-purple-100 rounded-lg p-4">
                            <i class="fas fa-comments text-4xl text-purple-600 mb-4"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Foro de Estudiantes</h3>
                        <p class="text-gray-600 mb-4">Comunidad activa de aprendizaje:</p>
                        <ul class="list-disc list-inside text-gray-600">
                            <li>Grupos de estudio</li>
                            <li>Discusiones académicas</li>
                            <li>Proyectos colaborativos</li>
                            <li>Eventos virtuales</li>
                            <li>Clubes de interés</li>
                            <li>Red de contactos</li>
                        </ul>
                    </div>
                </div>

                <!-- Calendario Académico -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center mb-4">
                        <div class="feature-image mb-4 bg-orange-100 rounded-lg p-4">
                            <i class="fas fa-calendar-alt text-4xl text-orange-600 mb-4"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Calendario Académico</h3>
                        <p class="text-gray-600 mb-4">Gestión completa de tu tiempo:</p>
                        <ul class="list-disc list-inside text-gray-600">
                            <li>Horarios de clases</li>
                            <li>Fechas de exámenes</li>
                            <li>Eventos académicos</li>
                            <li>Recordatorios personalizados</li>
                            <li>Planificador de tareas</li>
                            <li>Sincronización con calendario</li>
                        </ul>
                    </div>
                </div>

                <!-- Tutorías Virtuales -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center mb-4">
                        <div class="feature-image mb-4 bg-blue-100 rounded-lg p-4">
                            <i class="fas fa-user-graduate text-4xl text-blue-600 mb-4"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Tutorías Virtuales</h3>
                        <p class="text-gray-600 mb-4">Apoyo personalizado:</p>
                        <ul class="list-disc list-inside text-gray-600">
                            <li>Mentores expertos</li>
                            <li>Sesiones personalizadas</li>
                            <li>Seguimiento de progreso</li>
                            <li>Recursos adaptativos</li>
                            <li>Plan de estudios</li>
                            <li>Evaluaciones periódicas</li>
                        </ul>
                    </div>
                </div>

                <!-- Laboratorio Virtual -->
                <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center mb-4">
                        <div class="feature-image mb-4 bg-green-100 rounded-lg p-4">
                            <i class="fas fa-flask text-4xl text-green-600 mb-4"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Laboratorio Virtual</h3>
                        <p class="text-gray-600 mb-4">Experimentación digital:</p>
                        <ul class="list-disc list-inside text-gray-600">
                            <li>Simulaciones interactivas</li>
                            <li>Prácticas virtuales</li>
                            <li>Proyectos de investigación</li>
                            <li>Equipos de última tecnología</li>
                            <li>Experimentos controlados</li>
                            <li>Recursos de laboratorio</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sección de Beneficios -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-8">Beneficios del Campus Virtual</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Acceso Ilimitado -->
                    <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="text-center">
                            <div class="feature-image mb-4 bg-blue-100 rounded-lg p-4">
                                <i class="fas fa-globe text-4xl text-blue-600 mb-4"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-4">Acceso Ilimitado</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Acceso 24/7 a todas las funcionalidades</li>
                                <li>Compatibilidad con todos los dispositivos</li>
                                <li>Soporte técnico continuo</li>
                                <li>Actualizaciones automáticas</li>
                                <li>Conexión segura</li>
                                <li>Backup automático</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Recursos Avanzados -->
                    <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="text-center">
                            <div class="feature-image mb-4 bg-green-100 rounded-lg p-4">
                                <i class="fas fa-book-open text-4xl text-green-600 mb-4"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-4">Recursos Avanzados</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Materiales de última generación</li>
                                <li>Simulaciones interactivas</li>
                                <li>Contenido multimedia</li>
                                <li>Recursos personalizados</li>
                                <li>Material de investigación</li>
                                <li>Base de datos completa</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Comunidad Activa -->
                    <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="text-center">
                            <div class="feature-image mb-4 bg-purple-100 rounded-lg p-4">
                                <i class="fas fa-users text-4xl text-purple-600 mb-4"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-4">Comunidad Activa</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Red global de estudiantes</li>
                                <li>Grupos de estudio</li>
                                <li>Eventos virtuales</li>
                                <li>Proyectos colaborativos</li>
                                <li>Mentorías</li>
                                <li>Clubes de interés</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Soporte Personalizado -->
                    <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="text-center">
                            <div class="feature-image mb-4 bg-orange-100 rounded-lg p-4">
                                <i class="fas fa-user-shield text-4xl text-orange-600 mb-4"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-4">Soporte Personalizado</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Tutorías personalizadas</li>
                                <li>Seguimiento de progreso</li>
                                <li>Plan de estudios adaptado</li>
                                <li>Recursos personalizados</li>
                                <li>Evaluaciones periódicas</li>
                                <li>Asesoramiento académico</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tecnología Avanzada -->
                    <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="text-center">
                            <div class="feature-image mb-4 bg-blue-100 rounded-lg p-4">
                                <i class="fas fa-microchip text-4xl text-blue-600 mb-4"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-4">Tecnología Avanzada</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Plataforma de videoconferencia HD</li>
                                <li>Tableros interactivos</li>
                                <li>Simulaciones 3D</li>
                                <li>Realidad virtual</li>
                                <li>Inteligencia artificial</li>
                                <li>Cloud computing</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Desarrollo Profesional -->
                    <div class="feature-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="text-center">
                            <div class="feature-image mb-4 bg-green-100 rounded-lg p-4">
                                <i class="fas fa-briefcase text-4xl text-green-600 mb-4"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-4">Desarrollo Profesional</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Certificaciones</li>
                                <li>Proyectos prácticos</li>
                                <li>Prácticas profesionales</li>
                                <li>Red de contactos</li>
                                <li>Asesoramiento laboral</li>
                                <li>Programas de mentoría</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-12 text-center">
                <h3 class="text-xl font-semibold mb-4">¿Listo para comenzar tu aprendizaje?</h3>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 inline-block">
                    Regístrate ahora
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-between">
                <div>
                    <h4 class="text-lg font-semibold mb-4">MentorHub</h4>
                    <p class="text-gray-400">Plataforma educativa innovadora para el aprendizaje del futuro</p>
                </div>
                <div class="mt-8 md:mt-0">
                    <a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Regístrate</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
