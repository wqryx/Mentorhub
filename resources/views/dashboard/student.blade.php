<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorHub - Dashboard del Estudiante</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #e5edff;
            color: #3b82f6;
        }
        .sidebar-link.active {
            font-weight: 600;
        }
        .sidebar-link svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
        }
        .dashboard-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        .progress-bar {
            height: 0.5rem;
            border-radius: 9999px;
            background-color: #e5e7eb;
            overflow: hidden;
        }
        .progress-value {
            height: 100%;
            border-radius: 9999px;
            background-color: #3b82f6;
        }
        .calendar-cell {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
        }
        .calendar-cell.active {
            background-color: #3b82f6;
            color: white;
        }
        .module-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .resource-icon {
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 0.25rem;
            background-color: #e5edff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            margin-right: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white border-r border-gray-200 p-6">
            <div class="flex items-center mb-8">
                <svg class="w-10 h-10 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    <path d="M12 14l-9-5 9-5 9 5-9 5zm0 0v12"></path>
                </svg>
                <h1 class="text-xl font-bold">MENTORHUB</h1>
            </div>

            <nav class="space-y-1">
                <a href="#" class="sidebar-link active">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="sidebar-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Courses
                </a>
                <a href="#" class="sidebar-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Grades
                </a>
                <a href="#" class="sidebar-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Messages
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-semibold">Dashboard</h1>
                <button class="focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <!-- Perfil y Calendario -->
                <div class="col-span-12 md:col-span-4">
                    <!-- Perfil del Usuario -->
                    <div class="dashboard-card mb-6 flex flex-col items-center text-center">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John Doe" class="rounded-full w-24 h-24 mb-4">
                        <h2 class="text-xl font-semibold">John Doe</h2>
                        <p class="text-gray-500 mb-3">Web Development</p>
                        <div class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            Enrolled
                        </div>
                    </div>

                    <!-- Calendario -->
                    <div class="dashboard-card">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold">Calendar</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-2">
                            <div>S</div>
                            <div>M</div>
                            <div>T</div>
                            <div>W</div>
                            <div>T</div>
                            <div>F</div>
                            <div>S</div>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-1 text-center text-sm">
                            <div class="calendar-cell text-gray-400">-</div>
                            <div class="calendar-cell text-gray-400">-</div>
                            <div class="calendar-cell">1</div>
                            <div class="calendar-cell">2</div>
                            <div class="calendar-cell">3</div>
                            <div class="calendar-cell">4</div>
                            <div class="calendar-cell">5</div>
                            
                            <div class="calendar-cell">6</div>
                            <div class="calendar-cell">7</div>
                            <div class="calendar-cell">8</div>
                            <div class="calendar-cell">9</div>
                            <div class="calendar-cell">10</div>
                            <div class="calendar-cell">11</div>
                            <div class="calendar-cell">12</div>
                            
                            <div class="calendar-cell">13</div>
                            <div class="calendar-cell">14</div>
                            <div class="calendar-cell">15</div>
                            <div class="calendar-cell">16</div>
                            <div class="calendar-cell">17</div>
                            <div class="calendar-cell active">18</div>
                            <div class="calendar-cell">19</div>
                            
                            <div class="calendar-cell">20</div>
                            <div class="calendar-cell">21</div>
                            <div class="calendar-cell">22</div>
                            <div class="calendar-cell">23</div>
                            <div class="calendar-cell">24</div>
                            <div class="calendar-cell">25</div>
                            <div class="calendar-cell">27</div>
                            
                            <div class="calendar-cell">27</div>
                            <div class="calendar-cell">28</div>
                            <div class="calendar-cell">28</div>
                            <div class="calendar-cell">30</div>
                            <div class="calendar-cell text-gray-400">-</div>
                            <div class="calendar-cell text-gray-400">-</div>
                            <div class="calendar-cell text-gray-400">-</div>
                        </div>
                    </div>

                    <!-- Progreso Académico -->
                    <div class="dashboard-card mt-6">
                        <h3 class="font-semibold mb-4">Academic Progress</h3>
                        <div class="progress-bar mb-2">
                            <div class="progress-value" style="width: 75%"></div>
                        </div>
                        <p class="text-right text-sm font-medium">75%</p>
                    </div>
                </div>

                <!-- Módulos, Actividades y Mensajes -->
                <div class="col-span-12 md:col-span-4">
                    <!-- Módulos -->
                    <div class="dashboard-card">
                        <h3 class="font-semibold mb-4">Modules</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center p-3 border rounded-lg">
                                <div class="module-icon bg-yellow-500 mr-3">05</div>
                                <div>
                                    <h4 class="font-medium">Introduction to HTML</h4>
                                    <p class="text-xs text-gray-500">In progress</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-3 border rounded-lg">
                                <div class="module-icon bg-orange-500 mr-3">JC</div>
                                <div>
                                    <h4 class="font-medium">JavaScript Basics</h4>
                                    <p class="text-xs text-gray-500">In progress</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-3 border rounded-lg">
                                <div class="module-icon bg-green-500 mr-3">aa</div>
                                <div>
                                    <h4 class="font-medium">Responsive Design</h4>
                                    <p class="text-xs text-gray-500">In progress</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actividades Próximas -->
                    <div class="dashboard-card mt-6">
                        <h3 class="font-semibold mb-4">Upcoming Activities</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 border rounded-lg">
                                <div class="flex items-center">
                                    <div class="mr-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium">Assignment 2</h4>
                                        <p class="text-xs text-gray-500">April 20</p>
                                    </div>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 border rounded-lg">
                                <div class="flex items-center">
                                    <div class="mr-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium">Quiz 1</h4>
                                        <p class="text-xs text-gray-500">April 22</p>
                                    </div>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Noticias -->
                    <div class="dashboard-card mt-6">
                        <h3 class="font-semibold mb-4">News</h3>
                        
                        <div class="flex justify-between items-center p-3 border rounded-lg">
                            <div>
                                <h4 class="font-medium">New Internship Opportunities</h4>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tareas y Recursos -->
                <div class="col-span-12 md:col-span-4">
                    <!-- Asignaturas/Tareas -->
                    <div class="dashboard-card">
                        <h3 class="font-semibold mb-4">Assignuras</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 border rounded-lg">
                                <div>
                                    <h4 class="font-medium">Assignment 2</h4>
                                    <p class="text-xs text-gray-500">April 20</p>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 border rounded-lg">
                                <div>
                                    <h4 class="font-medium">Quiz 1</h4>
                                    <p class="text-xs text-gray-500">April 22</p>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mensajes -->
                    <div class="dashboard-card mt-6">
                        <h3 class="font-semibold mb-4">Messages</h3>
                        
                        <div class="flex items-start p-3 border rounded-lg">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Sarah Smith" class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <h4 class="font-medium">Sarah Smith</h4>
                                <p class="text-sm text-gray-600">Please review the attached document.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recursos -->
                    <div class="dashboard-card mt-6">
                        <h3 class="font-semibold mb-4">Resources and materials</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center p-3 border rounded-lg">
                                <div class="resource-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium">Lecture Notes</h4>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-3 border rounded-lg">
                                <div class="resource-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium">Project Guidelines</h4>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-3 border rounded-lg">
                                <div class="resource-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium">Reference Articles</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Este script se puede utilizar para manejar las interacciones del usuario
        document.addEventListener('DOMContentLoaded', function() {
            // Aquí puedes añadir funcionalidad para los botones y enlaces
            
            // Ejemplo: Botones de navegación del calendario
            const calendarNavButtons = document.querySelectorAll('.calendar-nav');
            calendarNavButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Aquí iría la lógica para cambiar el mes del calendario
                    console.log('Calendar navigation clicked');
                });
            });
            
            // Ejemplo: Enlaces del sidebar
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Prevenir navegación para este ejemplo
                    e.preventDefault();
                    
                    // Remover la clase active de todos los enlaces
                    sidebarLinks.forEach(l => l.classList.remove('active'));
                    
                    // Añadir la clase active al enlace clicado
                    this.classList.add('active');
                    
                    // Aquí iría la lógica para cargar el contenido correspondiente
                    console.log('Sidebar link clicked:', this.textContent.trim());
                });
            });
            
            // Ejemplo: Botones de tareas y actividades
            const activityButtons = document.querySelectorAll('.dashboard-card button');
            activityButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Aquí iría la lógica para ver detalles de la tarea o actividad
                    const activityName = this.closest('.flex').querySelector('h4').textContent;
                    console.log('Activity clicked:', activityName);
                });
            });
        });
    </script>
</body>
</html>
