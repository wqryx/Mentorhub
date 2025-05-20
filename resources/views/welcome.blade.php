<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MentorHub</title>
    <meta name="description" content="Plataforma de mentoría y aprendizaje que conecta estudiantes con mentores expertos">
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
                    <a href="/" class="flex items-center">
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
                    <a href="/admin/register" class="nav-link text-gray-700 hover:text-primary-color font-medium">Registro Admin</a>
                </nav>
                
                <!-- Botones de inicio de sesión / registro -->
                <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="{{ route('welcome') }}" class="flex items-center">
                        <svg class="h-8 w-8 text-primary-color" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-800">MentorHub</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('guest.login') }}" class="text-gray-600 hover:text-primary-color transition-colors">
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
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Iniciar Sesión</a>
                    <a href="/admin/register" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Registro Admin</a>
                    <a href="/admin/register" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Registro Admin</a>
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
                        <a href="/tutoriales" class="text-white border-2 border-white px-6 py-3 rounded-md font-medium hover:bg-white hover:bg-opacity-10 transition-all">
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
                    <a href="#" class="mt-6 inline-block btn-primary">Ver todos los cursos</a>
                </div>
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1171&q=80" alt="Tutoriales y cursos" class="rounded-lg shadow-xl">
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
                    <a href="#" class="mt-6 inline-block btn-primary">Encontrar mentor</a>
                </div>
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1515169067868-5387ec356754?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Sesiones de mentoría" class="rounded-lg shadow-xl">
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
                    <a href="#" class="mt-6 inline-block btn-primary">Explorar foros</a>
                </div>
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Foros de discusión" class="rounded-lg shadow-xl">
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
                            <a href="#" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-2.719 0-4.92 2.201-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 14-7.503 14-14v-.617c.943-.676 1.762-1.518 2.409-2.48z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-white hover:text-gray-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de contacto -->
                <div class="md:w-3/5 p-8">
                    <form action="#" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-color focus:border-primary-color">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-color focus:border-primary-color">
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Asunto</label>
                            <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-color focus:border-primary-color">
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
    // Toggle menu móvil
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if(mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>
</body>
</html>