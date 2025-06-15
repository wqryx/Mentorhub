<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MentorHub</title>
    <meta name="description" content="Plataforma de mentoría y aprendizaje que conecta estudiantes con mentores expertos">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-color': '#3f51b5',
                        'secondary-color': '#2196f3',
                        'accent-color': '#f44336',
                        'background-color': '#f9fafb',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .testimony-card {
            transition: transform 0.3s ease;
        }

        .testimony-card:hover {
            transform: scale(1.03);
        }

        .btn-primary {
            background-color: #3f51b5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #303f9f;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .btn-secondary {
            background-color: white;
            color: #3f51b5;
            border: 1px solid #3f51b5;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #f0f4ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #3f51b5 0%, #2196f3 100%);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #3f51b5;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .stat-card {
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-white text-gray-800">
    <!-- Header con navegación principal -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo y nombre del proyecto -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <svg class="h-8 w-8 text-primary-color" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-11h2v6h-2zm0-4h2v2h-2z"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-800">MentorHub</span>
                    </a>
                </div>
                
                <!-- Menú de navegación principal (desktop) -->
                <nav class="hidden md:flex space-x-8">
                    <a href="#inicio" class="nav-link text-gray-700 hover:text-primary-color font-medium">Inicio</a>
                    <a href="#cursos" class="nav-link text-gray-700 hover:text-primary-color font-medium">Tutoriales / Cursos</a>
                    <a href="#mentorias" class="nav-link text-gray-700 hover:text-primary-color font-medium">Mentorías</a>
                    <a href="#foros" class="nav-link text-gray-700 hover:text-primary-color font-medium">Foros</a>
                    <a href="#contacto" class="nav-link text-gray-700 hover:text-primary-color font-medium">Contacto</a>
                </nav>
                
                <!-- Botones de inicio de sesión / registro -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('guest') }}" class="text-gray-600 hover:text-primary-color transition-colors">
                        <svg class="h-5 w-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Acceso como invitado
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-color transition-colors">
                        <svg class="h-5 w-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="bg-primary-color text-white px-4 py-2 rounded-md hover:bg-primary-color-dark transition-colors">
                        Registrarse
                    </a>
                </div>
                
                <!-- Menú móvil (toggle) -->
                <div class="md:hidden flex items-center">
                    <button id="mobileMenuBtn" class="text-gray-700 hover:text-primary-color focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Menú móvil (expandible) -->
            <div id="mobileMenu" class="md:hidden hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#inicio" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Inicio</a>
                    <a href="#cursos" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Tutoriales / Cursos</a>
                    <a href="#mentorias" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Mentorías</a>
                    <a href="#foros" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Foros</a>
                    <a href="#contacto" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Contacto</a>
                    <div class="border-t border-gray-200 pt-4 pb-3">
                        <div class="flex items-center px-4">
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Iniciar sesión</a>
                            <a href="{{ route('register') }}" class="block ml-3 px-3 py-2 rounded-md text-base font-medium text-white bg-primary-color hover:bg-opacity-90">Registrarse</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="inicio" class="hero-gradient text-white py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                        Aprende, enseña y conecta con expertos
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 opacity-90">
                        Bienvenido a tu comunidad de aprendizaje.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="btn-primary bg-white text-primary-color hover:bg-gray-100">
                            Únete ahora
                        </a>
                        <a href="{{ route('tutorials.index') }}" class="text-white border-2 border-white px-6 py-3 rounded-md font-medium hover:bg-white hover:bg-opacity-10 transition-all">
                            Explorar tutoriales gratuitos
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <img src="https://cdn.pixabay.com/photo/2018/01/17/07/06/laptop-3087585_960_720.jpg" alt="Comunidad de aprendizaje" class="rounded-lg shadow-xl w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de Funcionalidades -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Lo que ofrecemos</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    MentorHub te ofrece todo lo que necesitas para impulsar tu aprendizaje y desarrollo profesional.
                </p>
            </div>
        </div>

        <!-- Tutoriales y Cursos -->
        <div id="cursos" class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-gray-50">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 md:pr-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Tutoriales y Cursos</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Accede a una biblioteca completa de tutoriales y cursos creados por expertos en cada campo.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Cursos completos con certificaciones reconocidas</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Videos, lecturas y ejercicios prácticos</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Aprendizaje a tu ritmo, accesible 24/7</span>
                        </li>
                    </ul>
                    <button onclick="showAllCourses()" class="mt-6 inline-block btn-primary">Ver todos los cursos</button>
                </div>
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1171&q=80" alt="Tutoriales y cursos" class="rounded-lg shadow-xl">
                </div>
                
                <!-- Modal de Cursos -->
                <div id="coursesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
                    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
                        <div class="p-6 border-b border-gray-200 flex-shrink-0">
                            <div class="flex justify-between items-center">
                                <h3 class="text-2xl font-bold text-gray-900">Nuestros Cursos</h3>
                                <button onclick="closeCoursesModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Contenedor principal con scroll -->
                        <div class="flex-1 overflow-y-auto">
                            <!-- Lista de cursos -->
                            <div id="coursesList" class="p-6">
                                <div id="coursesContainer" class="space-y-4">
                                    <!-- Los cursos se cargarán aquí dinámicamente -->
                                </div>
                            </div>
                            
                            <!-- Detalle del curso -->
                            <div id="courseDetail" class="hidden p-6">
                                <div id="courseDetailContent"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mentorías -->
        <div id="mentorias" class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-white">
            <div class="flex flex-col md:flex-row-reverse items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 md:pl-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Sesiones de Mentoría</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Conecta directamente con expertos del sector para recibir orientación personalizada.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Sesiones uno a uno con profesionales del sector</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Feedback personalizado sobre tus proyectos</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Orientación de carrera y desarrollo profesional</span>
                        </li>
                    </ul>
                    <button onclick="showAllMentors()" class="mt-6 inline-block btn-primary">Encontrar mentor</button>
                </div>
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1515169067868-5387ec356754?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Sesiones de mentoría" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>

        <!-- Modal de Mentores -->
        <div id="mentorsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Fondo del modal -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeMentorsModal()"></div>

                <!-- Contenido del modal -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <!-- Encabezado -->
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                    <h3 class="text-2xl font-bold text-gray-900" id="mentorsModalTitle">Nuestros Mentores</h3>
                                    <button type="button" onclick="closeMentorsModal()" class="text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Cerrar</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Contenido del modal -->
                                <div class="mt-4">
                                    <!-- Lista de mentores (se muestra por defecto) -->
                                    <div id="mentorsList">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="mentorsContainer">
                                            <!-- Los mentores se cargarán aquí dinámicamente -->
                                        </div>
                                    </div>

                                    <!-- Detalle del mentor (oculto inicialmente) -->
                                    <div id="mentorDetail" class="hidden">
                                        <div id="mentorDetailContent">
                                            <!-- El detalle del mentor se cargará aquí dinámicamente -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foros de Discusión -->
        <div id="foros" class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-gray-50">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 md:pr-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Foros de Discusión</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Únete a nuestra comunidad y participa en conversaciones enriquecedoras con otros estudiantes y profesionales.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Foros temáticos para diferentes áreas de conocimiento</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Resolución de dudas por la comunidad y expertos</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-primary-color mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Networking con personas de intereses similares</span>
                        </li>
                    </ul>
                    <button onclick="showAllForums()" class="mt-6 inline-block btn-primary">Explorar foros</button>
                </div>
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Foros de discusión" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>

        <!-- Modal de Foros -->
        <div id="forumsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Fondo del modal -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeForumsModal()"></div>

                <!-- Contenido del modal -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <!-- Encabezado -->
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                    <h3 class="text-2xl font-bold text-gray-900" id="forumsModalTitle">Foros de Discusión</h3>
                                    <button type="button" onclick="closeForumsModal()" class="text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Cerrar</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Contenido del modal -->
                                <div class="mt-4">
                                    <!-- Lista de foros (se muestra por defecto) -->
                                    <div id="forumsList">
                                        <div class="space-y-4" id="forumsContainer">
                                            <!-- Los foros se cargarán aquí dinámicamente -->
                                        </div>
                                    </div>

                                    <!-- Detalle del foro (oculto inicialmente) -->
                                    <div id="forumDetail" class="hidden">
                                        <div id="forumDetailContent">
                                            <!-- El detalle del foro se cargará aquí dinámicamente -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Lo que ofrecemos</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    MentorHub te ofrece todo lo que necesitas para impulsar tu aprendizaje y desarrollo profesional.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Tutoriales y Cursos -->
                <div class="feature-card bg-white rounded-lg p-6 shadow hover:shadow-lg">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-primary-color" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Tutoriales y cursos</h3>
                    <p class="text-gray-600">
                        Accede a una amplia biblioteca de tutoriales y cursos en diferentes áreas y niveles de dificultad.
                    </p>
                </div>
                
                <!-- Mentorías -->
                <div class="feature-card bg-white rounded-lg p-6 shadow hover:shadow-lg">
                    <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Sesiones de mentoría</h3>
                    <p class="text-gray-600">
                        Conecta con mentores expertos para sesiones 1 a 1 o grupales y acelera tu aprendizaje.
                    </p>
                </div>
                
                <!-- Foros -->
                <div class="feature-card bg-white rounded-lg p-6 shadow hover:shadow-lg">
                    <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Foros de discusión</h3>
                    <p class="text-gray-600">
                        Participa en foros temáticos donde puedes resolver dudas y compartir conocimientos con la comunidad.
                    </p>
                </div>
                
                <!-- Comunidad -->
                <div class="feature-card bg-white rounded-lg p-6 shadow hover:shadow-lg">
                    <div class="w-14 h-14 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Comunidad activa</h3>
                    <p class="text-gray-600">
                        Únete a una comunidad vibrante con chat en tiempo real y eventos virtuales regulares.
                    </p>
                </div>
            </div>
        </div>
    </section>
<!-- Sección de Llamadas a la Acción (CTA) -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">¿Listo para comenzar tu viaje de aprendizaje?</h2>
            <p class="text-lg text-gray-600 mb-8">
                Únete a miles de estudiantes y mentores en nuestra plataforma y lleva tus habilidades al siguiente nivel.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('register') }}" class="btn-primary text-center">
                    Únete ahora
                </a>
                <a href="/tutoriales" class="btn-secondary text-center">
                    Explorar tutoriales gratuitos
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Testimonios -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Lo que dicen nuestros usuarios</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Descubre cómo MentorHub ha transformado la experiencia de aprendizaje para estudiantes y profesionales.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Testimonio 1 -->
            <div class="testimony-card bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-full object-cover mr-4" src="https://randomuser.me/api/portraits/women/32.jpg" alt="Ana Martínez">
                    <div>
                        <h4 class="font-semibold text-gray-800">Ana Martínez</h4>
                        <p class="text-sm text-gray-500">Estudiante de Diseño UX</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Gracias a las mentorías personalizadas, pude construir un portafolio que me ayudó a conseguir mi primer trabajo en una agencia de diseño. ¡Los mentores son increíbles!"
                </p>
                <div class="flex mt-4">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <!-- Repite este SVG para mostrar 5 estrellas -->
                </div>
            </div>
            
            <!-- Testimonio 2 -->
            <div class="testimony-card bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-full object-cover mr-4" src="https://randomuser.me/api/portraits/men/44.jpg" alt="Carlos Ramírez">
                    <div>
                        <h4 class="font-semibold text-gray-800">Carlos Ramírez</h4>
                        <p class="text-sm text-gray-500">Desarrollador Web</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Los tutoriales y los foros me han ayudado enormemente a resolver problemas complejos de programación. La comunidad es muy activa y siempre hay alguien dispuesto a ayudar."
                </p>
                <div class="flex mt-4">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <!-- Repite este SVG para mostrar 4 estrellas -->
                </div>
            </div>
            
            <!-- Testimonio 3 -->
            <div class="testimony-card bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-full object-cover mr-4" src="https://randomuser.me/api/portraits/women/68.jpg" alt="Laura Sánchez">
                    <div>
                        <h4 class="font-semibold text-gray-800">Laura Sánchez</h4>
                        <p class="text-sm text-gray-500">Mentora en Marketing Digital</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Como mentora, la plataforma me ha permitido conectar con estudiantes de todo el mundo. Es gratificante ver cómo progresan y aplican lo aprendido en sus proyectos."
                </p>
                <div class="flex mt-4">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <!-- Repite este SVG para mostrar 5 estrellas -->
                </div>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
            <div class="stat-card bg-primary-color bg-opacity-10 p-6 text-center">
                <h3 class="text-4xl font-bold text-primary-color mb-2">5,000+</h3>
                <p class="text-gray-600">Estudiantes activos</p>
            </div>
            <div class="stat-card bg-secondary-color bg-opacity-10 p-6 text-center">
                <h3 class="text-4xl font-bold text-secondary-color mb-2">100+</h3>
                <p class="text-gray-600">Mentores expertos</p>
            </div>
            <div class="stat-card bg-accent-color bg-opacity-10 p-6 text-center">
                <h3 class="text-4xl font-bold text-accent-color mb-2">500+</h3>
                <p class="text-gray-600">Tutoriales disponibles</p>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Contacto -->
<section id="contacto" class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Ponte en contacto con nosotros</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Estamos aquí para responder a tus preguntas y recibir tus sugerencias. Compártenos tus inquietudes.
            </p>
        </div>

        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row bg-white rounded-lg overflow-hidden shadow-lg">
                <!-- Información de contacto -->
                <div class="bg-primary-color text-white md:w-2/5 p-8">
                    <h3 class="text-2xl font-bold mb-6">Información de contacto</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <p class="font-medium mb-1">Dirección</p>
                                <p class="opacity-90">Calle Principal 123, Madrid, España</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <p class="font-medium mb-1">Teléfono</p>
                                <p class="opacity-90">+34 900 123 456</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="font-medium mb-1">Email</p>
                                <p class="opacity-90">info@mentorhub.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10">
                        <h4 class="font-medium mb-3">Síguenos</h4>
                        <div class="flex space-x-4">
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4z"/>
                                </svg>
                            </a>
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-2.719 0-4.92 2.201-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 14-7.503 14-14v-.617c.943-.676 1.762-1.518 2.409-2.48z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de contacto -->
                <div class="md:w-3/5 p-8">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                            <ul class="list-disc list-inside text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-color focus:border-primary-color" required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-color focus:border-primary-color" required>
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Asunto</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-color focus:border-primary-color" required>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label>
                            <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-color focus:border-primary-color"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full btn-primary py-3">Enviar mensaje</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-white pt-12 pb-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <!-- Columna 1: Acerca de -->
            <div>
                <h3 class="text-xl font-semibold mb-4">MentorHub</h3>
                <p class="text-gray-400 mb-4">
                    Plataforma líder de aprendizaje y mentoría que conecta a estudiantes con expertos para impulsar el crecimiento profesional y personal a través de tutorías, cursos y una comunidad colaborativa.
                </p>
                <div class="flex space-x-4 mt-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Columna 2: Enlaces rápidos -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Explora</h3>
                <ul class="space-y-2">
                    <li><a href="#inicio" class="text-gray-400 hover:text-white transition-colors">Inicio</a></li>
                    <li><a href="#cursos" class="text-gray-400 hover:text-white transition-colors">Tutoriales y Cursos</a></li>
                    <li><a href="#mentorias" class="text-gray-400 hover:text-white transition-colors">Sesiones de Mentoría</a></li>
                    <li><a href="#foros" class="text-gray-400 hover:text-white transition-colors">Foros de Discusión</a></li>
                    <li><a href="#contacto" class="text-gray-400 hover:text-white transition-colors">Contacto</a></li>
                    <li><a href="/blog" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                    <li><a href="/eventos" class="text-gray-400 hover:text-white transition-colors">Eventos</a></li>
                </ul>
            </div>
            
            <!-- Columna 3: Legal y Recursos -->
            <div class="grid grid-cols-1 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="/terminos" class="text-gray-400 hover:text-white transition-colors">Términos de servicio</a></li>
                        <li><a href="/privacidad" class="text-gray-400 hover:text-white transition-colors">Política de privacidad</a></li>
                        <li><a href="/cookies" class="text-gray-400 hover:text-white transition-colors">Política de cookies</a></li>
                        <li><a href="/accesibilidad" class="text-gray-400 hover:text-white transition-colors">Accesibilidad</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Recursos</h3>
                    <ul class="space-y-2">
                        <li><a href="/faq" class="text-gray-400 hover:text-white transition-colors">Preguntas frecuentes</a></li>
                        <li><a href="/guias" class="text-gray-400 hover:text-white transition-colors">Guías de aprendizaje</a></li>
                        <li><a href="/comunidad" class="text-gray-400 hover:text-white transition-colors">Comunidad</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Columna 4: Newsletter y contacto -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Manténte conectado</h3>
                <p class="text-gray-400 mb-4">Suscríbete a nuestro newsletter para recibir las últimas noticias y ofertas especiales.</p>
                <form action="#" method="POST" class="mb-4">
                    <div class="flex">
                        <input type="email" placeholder="Tu email" class="w-full px-4 py-2 rounded-l-md focus:outline-none">
                        <button type="submit" class="bg-primary-color px-4 py-2 rounded-r-md hover:bg-opacity-90 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </form>
                <div class="mt-6">
                    <h4 class="font-semibold mb-2">Contacto rápido</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-400">info@mentorhub.com</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-400">+34 900 123 456</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-gray-400">Madrid, España</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-700 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    &copy; {{ date('Y') }} MentorHub. Todos los derechos reservados.
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Centro de ayuda</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Trabaja con nosotros</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Afiliados</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Mapa del sitio</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script>
    // Datos de ejemplo para foros (en un entorno real, estos datos vendrían de una API)
    const forumsData = [
        {
            id: 1,
            title: 'Desarrollo Web',
            description: 'Discusiones sobre desarrollo web frontend y backend, frameworks, mejores prácticas y más.',
            icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            members: 1245,
            topics: 342,
            latestPost: {
                title: 'Mejores prácticas para React 18',
                author: 'Ana Torres',
                time: 'Hace 2 horas',
                authorImage: 'https://randomuser.me/api/portraits/women/44.jpg'
            },
            popularTopics: [
                '¿Vue.js vs React en 2023?',
                'Mejores prácticas de accesibilidad web',
                'Cómo optimizar el rendimiento en Next.js'
            ]
        },
        {
            id: 2,
            title: 'Diseño UX/UI',
            description: 'Comparte y aprende sobre diseño de experiencia de usuario, interfaces, herramientas de diseño y tendencias.',
            icon: 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
            members: 987,
            topics: 256,
            latestPost: {
                title: 'Novedades en Figma 2023',
                author: 'Carlos Martínez',
                time: 'Ayer',
                authorImage: 'https://randomuser.me/api/portraits/men/32.jpg'
            },
            popularTopics: [
                'Diseño para móvil primero',
                'Sistemas de diseño en equipo',
                'Herramientas de prototipado'
            ]
        },
        {
            id: 3,
            title: 'Data Science',
            description: 'Todo sobre ciencia de datos, machine learning, inteligencia artificial y análisis de datos.',
            icon: 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
            members: 1567,
            topics: 421,
            latestPost: {
                title: 'Introducción a TensorFlow 2.0',
                author: 'Javier López',
                time: 'Hace 3 días',
                authorImage: 'https://randomuser.me/api/portraits/men/45.jpg'
            },
            popularTopics: [
                'Python para análisis de datos',
                'Redes neuronales desde cero',
                'Ética en IA'
            ]
        },
        {
            id: 4,
            title: 'Marketing Digital',
            description: 'Estrategias de marketing digital, SEO, redes sociales, contenido y crecimiento de audiencia.',
            icon: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
            members: 2034,
            topics: 512,
            latestPost: {
                title: 'Nuevas tendencias en SEO para 2023',
                author: 'María González',
                time: 'Hace 1 semana',
                authorImage: 'https://randomuser.me/api/portraits/women/68.jpg'
            },
            popularTopics: [
                'Estrategias de contenido',
                'Publicidad en redes sociales',
                'Marketing de afiliados'
            ]
        }
    ];

    // Datos de ejemplo de mentores (en un entorno real, estos datos vendrían de una API)
    const mentorsData = [
        {
            id: 1,
            name: 'Carlos Martínez',
            title: 'Ingeniero de Software Senior',
            company: 'Google',
            experience: '10+ años',
            image: 'https://randomuser.me/api/portraits/men/32.jpg',
            bio: 'Especializado en desarrollo backend y arquitectura de software. Apasionado por enseñar y compartir conocimiento.',
            expertise: ['JavaScript', 'Node.js', 'Arquitectura de Software', 'Bases de Datos'],
            rate: '$80/hora',
            availability: 'Lun-Vie 9am-6pm',
            languages: ['Español', 'Inglés']
        },
        {
            id: 2,
            name: 'Ana Torres',
            title: 'Diseñadora UX/UI',
            company: 'Freelance',
            experience: '8+ años',
            image: 'https://randomuser.me/api/portraits/women/44.jpg',
            bio: 'Diseñadora de experiencia de usuario con amplia experiencia en productos digitales. Me encanta ayudar a otros a mejorar sus habilidades de diseño.',
            expertise: ['Diseño UX/UI', 'Figma', 'Investigación de Usuarios', 'Prototipado'],
            rate: '$70/hora',
            availability: 'Lun-Jue 10am-4pm',
            languages: ['Español', 'Inglés', 'Portugués']
        },
        {
            id: 3,
            name: 'Javier López',
            title: 'Científico de Datos',
            company: 'Amazon',
            experience: '7+ años',
            image: 'https://randomuser.me/api/portraits/men/45.jpg',
            bio: 'Experto en machine learning y análisis de datos. He trabajado en proyectos de gran escala para empresas de tecnología.',
            expertise: ['Python', 'Machine Learning', 'Análisis de Datos', 'SQL'],
            rate: '$90/hora',
            availability: 'Mar-Jue 2pm-8pm',
            languages: ['Español', 'Inglés']
        },
        {
            id: 4,
            name: 'María González',
            title: 'Desarrolladora Frontend',
            company: 'Microsoft',
            experience: '5+ años',
            image: 'https://randomuser.me/api/portraits/women/68.jpg',
            bio: 'Especialista en desarrollo frontend con React y TypeScript. Me encanta crear interfaces de usuario accesibles y de alto rendimiento.',
            expertise: ['React', 'TypeScript', 'CSS', 'Accesibilidad'],
            rate: '$75/hora',
            availability: 'Lun-Vie 10am-7pm',
            languages: ['Español', 'Inglés']
        }
    ];

    // Datos de ejemplo de cursos (en un entorno real, estos datos vendrían de una API)
    const coursesData = [
        {
            id: 1,
            title: 'Introducción a la Programación',
            description: 'Aprende los fundamentos de la programación con ejemplos prácticos y proyectos reales.',
            image: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80',
            duration: '8 semanas',
            level: 'Principiante',
            instructor: 'Carlos Méndez',
            instructorImage: 'https://randomuser.me/api/portraits/men/32.jpg'
        },
        {
            id: 2,
            title: 'Diseño Web Moderno',
            description: 'Domina las últimas tecnologías para crear sitios web atractivos y responsivos.',
            image: 'https://images.unsplash.com/photo-1547658719-da2b51169166?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1064&q=80',
            duration: '6 semanas',
            level: 'Intermedio',
            instructor: 'Ana Torres',
            instructorImage: 'https://randomuser.me/api/portraits/women/68.jpg'
        },
        {
            id: 3,
            title: 'Data Science para Principiantes',
            description: 'Introducción al análisis de datos y machine learning con Python.',
            image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80',
            duration: '10 semanas',
            level: 'Intermedio',
            instructor: 'María González',
            instructorImage: 'https://randomuser.me/api/portraits/women/44.jpg'
        },
        {
            id: 4,
            title: 'Marketing Digital Avanzado',
            description: 'Estrategias avanzadas de marketing digital para hacer crecer tu negocio en línea.',
            image: 'https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1474&q=80',
            duration: '6 semanas',
            level: 'Avanzado',
            instructor: 'Javier López',
            instructorImage: 'https://randomuser.me/api/portraits/men/45.jpg'
        }
    ];

    // Referencias a elementos del DOM
    const coursesModal = document.getElementById('coursesModal');
    const coursesList = document.getElementById('coursesList');
    const courseDetail = document.getElementById('courseDetail');
    const courseDetailContent = document.getElementById('courseDetailContent');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    // Mostrar el modal de cursos
    function showAllCourses() {
        // Mostrar el modal
        coursesModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Cargar la lista de cursos
        loadCourses();
    }
    
    // Cerrar el modal de cursos
    function closeCoursesModal() {
        coursesModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Cargar la lista de cursos
    function loadCourses() {
        // Mostrar la lista de cursos y ocultar el detalle
        if (coursesList) coursesList.classList.remove('hidden');
        if (courseDetail) courseDetail.classList.add('hidden');
        
        // Limpiar la lista actual
        const coursesContainer = document.getElementById('coursesContainer');
        if (coursesContainer) {
            coursesContainer.innerHTML = '';
            
            // Verificar si hay cursos para mostrar
            if (coursesData && coursesData.length > 0) {
                // Agregar cada curso a la lista
                coursesData.forEach(course => {
                    const courseElement = document.createElement('div');
                    courseElement.className = 'p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors';
                    courseElement.innerHTML = `
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-24 h-24 bg-gray-200 rounded-md overflow-hidden">
                                <img src="${course.image}" alt="${course.title}" class="w-full h-full object-cover">
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="font-semibold text-gray-900 text-lg">${course.title}</h4>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">${course.description}</p>
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ${course.level}
                                    </span>
                                    <span class="ml-2 text-xs text-gray-500">
                                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ${course.duration}
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <img src="${course.instructorImage}" alt="${course.instructor}" class="w-5 h-5 rounded-full mr-2">
                                    <span>${course.instructor}</span>
                                </div>
                            </div>
                            <div class="ml-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    `;
                    
                    // Agregar evento de clic para mostrar el detalle del curso
                    courseElement.addEventListener('click', () => showCourseDetail(course.id));
                    
                    coursesContainer.appendChild(courseElement);
                });
            } else {
                // Mostrar mensaje si no hay cursos
                const emptyMessage = document.createElement('div');
                emptyMessage.className = 'text-center py-12';
                emptyMessage.innerHTML = `
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No hay cursos disponibles</h3>
                    <p class="mt-1 text-sm text-gray-500">Por el momento no hay cursos disponibles. Vuelve a intentarlo más tarde.</p>
                `;
                coursesContainer.appendChild(emptyMessage);
            }
        }
    }
    
    // Mostrar el detalle de un curso específico
    function showCourseDetail(courseId) {
        const course = coursesData.find(c => c.id === courseId);
        if (!course) return;
        
        // Ocultar la lista y mostrar el detalle
        if (coursesList) coursesList.classList.add('hidden');
        if (courseDetail) {
            courseDetail.classList.remove('hidden');
            
            // Actualizar el contenido del detalle
            courseDetailContent.innerHTML = `
                <div class="mb-6">
                    <!-- Botón de volver -->
                    <button onclick="loadCourses()" class="text-primary-600 hover:text-primary-800 font-medium flex items-center mb-6 group">
                        <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Volver a los cursos
                    </button>
                    
                    <!-- Imagen del curso -->
                    <div class="relative h-56 bg-gray-100 rounded-xl overflow-hidden mb-6">
                        <img src="${course.image}" alt="${course.title}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 w-full">
                            <div class="flex items-center">
                                <img src="${course.instructorImage}" alt="${course.instructor}" class="w-10 h-10 rounded-full border-2 border-white mr-3">
                                <div>
                                    <p class="text-sm text-white/80">Instructor</p>
                                    <p class="font-medium text-white">${course.instructor}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Título y descripción -->
                    <div class="mb-6">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">${course.title}</h2>
                        <p class="text-gray-600 leading-relaxed">${course.description}</p>
                    </div>
                    
                    <!-- Información del curso -->
                    <div class="bg-gray-50 p-5 rounded-xl mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Información del curso</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Duración</p>
                                    <p class="font-medium text-gray-900">${course.duration}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Nivel</p>
                                    <p class="font-medium text-gray-900">${course.level}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Instructor</p>
                                    <p class="font-medium text-gray-900">${course.instructor}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Modalidad</p>
                                    <p class="font-medium text-gray-900">En línea • A tu ritmo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lo que aprenderás -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Lo que aprenderás</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Contenido detallado y práctico con ejemplos del mundo real</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Ejercicios prácticos y proyectos para aplicar lo aprendido</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Acceso a comunidad exclusiva de estudiantes</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Certificado de finalización al completar el curso</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón de acción -->
                    <div class="pt-4 border-t border-gray-200">
                        <a href="#" class="block w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-lg text-center transition-colors">
                            Inscribirse ahora
                        </a>
                    </div>
                </div>
            `;
        }
    }
    
    // Cerrar el modal al hacer clic fuera del contenido
    if (coursesModal) {
        coursesModal.addEventListener('click', (e) => {
            if (e.target === coursesModal) {
                closeCoursesModal();
            }
        });
    }
    
    // Toggle menu móvil
    if(mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Referencias a elementos del DOM para mentores
    const mentorsModal = document.getElementById('mentorsModal');
    const mentorsList = document.getElementById('mentorsList');
    const mentorDetail = document.getElementById('mentorDetail');
    const mentorDetailContent = document.getElementById('mentorDetailContent');
    const mentorsContainer = document.getElementById('mentorsContainer');

    // Mostrar el modal de mentores
    function showAllMentors() {
        // Mostrar el modal
        mentorsModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Cargar la lista de mentores
        loadMentors();
    }
    
    // Cerrar el modal de mentores
    function closeMentorsModal() {
        mentorsModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Cargar la lista de mentores
    function loadMentors() {
        // Mostrar la lista de mentores y ocultar el detalle
        if (mentorsList) mentorsList.classList.remove('hidden');
        if (mentorDetail) mentorDetail.classList.add('hidden');
        
        // Limpiar el contenedor actual
        if (mentorsContainer) {
            mentorsContainer.innerHTML = '';
            
            // Verificar si hay mentores para mostrar
            if (mentorsData && mentorsData.length > 0) {
                // Agregar cada mentor a la lista
                mentorsData.forEach(mentor => {
                    const mentorElement = document.createElement('div');
                    mentorElement.className = 'p-4 border border-gray-200 rounded-lg hover:shadow-md cursor-pointer transition-shadow';
                    mentorElement.innerHTML = `
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-full overflow-hidden">
                                <img src="${mentor.image}" alt="${mentor.name}" class="w-full h-full object-cover">
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="font-semibold text-gray-900">${mentor.name}</h4>
                                <p class="text-sm text-gray-600">${mentor.title} • ${mentor.company}</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ${mentor.experience} de experiencia
                                    </span>
                                </div>
                                <div class="mt-2">
                                    ${mentor.expertise.slice(0, 3).map(skill => 
                                        `<span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs text-gray-700 mr-1 mb-1">${skill}</span>`
                                    ).join('')}
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900">${mentor.rate}</span>
                                    <button onclick="showMentorDetail(${mentor.id}, event)" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        Ver perfil
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Agregar el elemento al contenedor
                    mentorsContainer.appendChild(mentorElement);
                });
            } else {
                // Mostrar mensaje si no hay mentores
                mentorsContainer.innerHTML = `
                    <div class="col-span-2 text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay mentores disponibles</h3>
                        <p class="mt-1 text-sm text-gray-500">Pronto agregaremos más mentores a nuestra plataforma.</p>
                    </div>
                `;
            }
        }
    }
    
    // Mostrar el detalle de un mentor
    function showMentorDetail(mentorId, event) {
        // Prevenir la navegación si se hizo clic en un enlace
        if (event) {
            event.stopPropagation();
        }
        
        const mentor = mentorsData.find(m => m.id === mentorId);
        if (!mentor) return;
        
        // Ocultar la lista y mostrar el detalle
        if (mentorsList) mentorsList.classList.add('hidden');
        if (mentorDetail) {
            mentorDetail.classList.remove('hidden');
            
            // Actualizar el contenido del detalle
            mentorDetailContent.innerHTML = `
                <div class="mb-6">
                    <!-- Botón de volver -->
                    <button onclick="loadMentors()" class="text-primary-600 hover:text-primary-800 font-medium flex items-center mb-6 group">
                        <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Volver a la lista de mentores
                    </button>
                    
                    <!-- Encabezado del mentor -->
                    <div class="flex flex-col md:flex-row md:items-start">
                        <div class="md:mr-8 mb-6 md:mb-0">
                            <div class="w-32 h-32 bg-gray-200 rounded-full overflow-hidden shadow-md">
                                <img src="${mentor.image}" alt="${mentor.name}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900">${mentor.name}</h2>
                            <p class="text-lg text-gray-600 mt-1">${mentor.title} en ${mentor.company}</p>
                            
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    ${mentor.experience} de experiencia
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ${mentor.rate}
                                </span>
                            </div>
                            
                            <div class="mt-4">
                                <h3 class="text-sm font-medium text-gray-500">Disponibilidad</h3>
                                <p class="text-gray-900">${mentor.availability}</p>
                            </div>
                            
                            <div class="mt-2">
                                <h3 class="text-sm font-medium text-gray-500">Idiomas</h3>
                                <p class="text-gray-900">${mentor.languages.join(', ')}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Biografía -->
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Sobre mí</h3>
                        <p class="text-gray-700">${mentor.bio}</p>
                    </div>
                    
                    <!-- Habilidades -->
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Habilidades y experiencia</h3>
                        <div class="flex flex-wrap gap-2">
                            ${mentor.expertise.map(skill => 
                                `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    ${skill}
                                </span>`
                            ).join('')}
                        </div>
                    </div>
                    
                    <!-- Botón de contacto -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <button class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-lg text-center transition-colors">
                            Contactar a ${mentor.name.split(' ')[0]}
                        </button>
                    </div>
                </div>
            `;
        }
    }
    
    // Cerrar el modal al hacer clic fuera del contenido
    if (mentorsModal) {
        mentorsModal.addEventListener('click', (e) => {
            if (e.target === mentorsModal) {
                closeMentorsModal();
            }
        });
    }

    // Referencias a elementos del DOM para foros
    const forumsModal = document.getElementById('forumsModal');
    const forumsList = document.getElementById('forumsList');
    const forumDetail = document.getElementById('forumDetail');
    const forumDetailContent = document.getElementById('forumDetailContent');
    const forumsContainer = document.getElementById('forumsContainer');

    // Mostrar el modal de foros
    function showAllForums() {
        // Mostrar el modal
        forumsModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Cargar la lista de foros
        loadForums();
    }
    
    // Cerrar el modal de foros
    function closeForumsModal() {
        forumsModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Cargar la lista de foros
    function loadForums() {
        // Mostrar la lista de foros y ocultar el detalle
        if (forumsList) forumsList.classList.remove('hidden');
        if (forumDetail) forumDetail.classList.add('hidden');
        
        // Limpiar el contenedor actual
        if (forumsContainer) {
            forumsContainer.innerHTML = '';
            
            // Verificar si hay foros para mostrar
            if (forumsData && forumsData.length > 0) {
                // Agregar cada foro a la lista
                forumsData.forEach(forum => {
                    const forumElement = document.createElement('div');
                    forumElement.className = 'p-5 border border-gray-200 rounded-lg hover:shadow-md cursor-pointer transition-shadow';
                    forumElement.innerHTML = `
                        <div class="flex">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${forum.icon}" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">${forum.title}</h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">${forum.description}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="mr-4">${forum.members.toLocaleString()} miembros</span>
                                    <span>${forum.topics.toLocaleString()} temas</span>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="flex items-center text-sm">
                                        <div class="flex-shrink-0">
                                            <img class="h-6 w-6 rounded-full" src="${forum.latestPost.authorImage}" alt="${forum.latestPost.author}">
                                        </div>
                                        <div class="ml-2 truncate">
                                            <p class="text-sm font-medium text-gray-900 truncate">${forum.latestPost.title}</p>
                                            <p class="text-xs text-gray-500">Por ${forum.latestPost.author} • ${forum.latestPost.time}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    `;
                    
                    // Agregar evento de clic para mostrar el detalle
                    forumElement.addEventListener('click', () => showForumDetail(forum.id));
                    
                    // Agregar el elemento al contenedor
                    forumsContainer.appendChild(forumElement);
                });
            } else {
                // Mostrar mensaje si no hay foros
                forumsContainer.innerHTML = `
                    <div class="col-span-2 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay foros disponibles</h3>
                        <p class="mt-1 text-sm text-gray-500">Pronto agregaremos más foros a nuestra plataforma.</p>
                    </div>
                `;
            }
        }
    }
    
    // Mostrar el detalle de un foro
    function showForumDetail(forumId) {
        const forum = forumsData.find(f => f.id === forumId);
        if (!forum) return;
        
        // Ocultar la lista y mostrar el detalle
        if (forumsList) forumsList.classList.add('hidden');
        if (forumDetail) {
            forumDetail.classList.remove('hidden');
            
            // Actualizar el contenido del detalle
            forumDetailContent.innerHTML = `
                <div class="mb-6">
                    <!-- Botón de volver -->
                    <button onclick="loadForums()" class="text-primary-600 hover:text-primary-800 font-medium flex items-center mb-6 group">
                        <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver a la lista de foros
                    </button>
                    
                    <!-- Encabezado del foro -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${forum.icon}" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">${forum.title}</h2>
                                <p class="text-gray-600">${forum.members.toLocaleString()} miembros • ${forum.topics.toLocaleString()} temas</p>
                            </div>
                        </div>
                        <button class="mt-4 md:mt-0 bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Nuevo tema
                        </button>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Descripción</h3>
                        <p class="text-gray-700">${forum.description}</p>
                    </div>
                    
                    <!-- Último tema -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Último tema</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full" src="${forum.latestPost.authorImage}" alt="${forum.latestPost.author}">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">${forum.latestPost.title}</p>
                                    <p class="text-sm text-gray-500">Publicado por ${forum.latestPost.author} • ${forum.latestPost.time}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Temas populares -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Temas populares</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                ${forum.popularTopics.map((topic, index) => `
                                    <li class="px-4 py-4 hover:bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-primary-600 truncate">${topic}</p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ${Math.floor(Math.random() * 50) + 10} respuestas
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Ver todos los temas -->
                    <div class="mt-6 text-center">
                        <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                            Ver todos los temas de ${forum.title} →
                        </a>
                    </div>
                </div>
            `;
        }
    }
    
    // Cerrar el modal al hacer clic fuera del contenido
    if (forumsModal) {
        forumsModal.addEventListener('click', (e) => {
            if (e.target === forumsModal) {
                closeForumsModal();
            }
        });
    }
</script>
</body>
</html>