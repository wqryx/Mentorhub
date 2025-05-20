<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
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
                    <a href="/" class="nav-link text-gray-700 hover:text-primary-color font-medium">Inicio</a>
                    <a href="/tutoriales" class="nav-link text-gray-700 hover:text-primary-color font-medium">Tutoriales / Cursos</a>
                    <a href="/mentorias" class="nav-link text-gray-700 hover:text-primary-color font-medium">Mentorías</a>
                    <a href="/foros" class="nav-link text-gray-700 hover:text-primary-color font-medium">Foros</a>
                    <a href="/contacto" class="nav-link text-gray-700 hover:text-primary-color font-medium">Contacto</a>
                </nav>
                
                <!-- Botones de inicio de sesión / registro -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-color font-medium">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn-primary">Registrarse</a>
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
                    <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Inicio</a>
                    <a href="/tutoriales" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Tutoriales / Cursos</a>
                    <a href="/mentorias" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Mentorías</a>
                    <a href="/foros" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Foros</a>
                    <a href="/contacto" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-color hover:bg-gray-50">Contacto</a>
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
