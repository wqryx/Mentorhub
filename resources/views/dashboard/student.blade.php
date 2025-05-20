@extends('layouts.app')

@section('content')
<div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1>MentorHub</h1>
            <div class="user-info">
                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/default-avatar.png') }}" 
                     alt="{{ $user->name }}" 
                     class="w-12 h-12 rounded-full">
                <div>
                    <h3 class="text-white">{{ $user->name }}</h3>
                    <p class="text-gray-300">{{ $user->course }} - {{ $user->cycle }}</p>
                </div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="#">
                <i class="fas fa-book"></i>
                <span>Cursos</span>
            </a>
            <a href="#">
                <i class="fas fa-chart-bar"></i>
                <span>Calificaciones</span>
            </a>
            <a href="#">
                <i class="fas fa-envelope"></i>
                <span>Mensajes</span>
            </a>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
        <!-- Perfil -->
        <section class="profile">
            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/default-avatar.png') }}" 
                 alt="{{ $user->name }}" 
                 class="w-20 h-20 rounded-full border-2 border-primary-color">
            <div class="profile-info">
                <h2 class="text-xl font-bold text-primary-color">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->course }} - {{ $user->cycle }}</p>
            </div>
        </section>

        <!-- Grid de tarjetas -->
        <section class="grid">
            <!-- Módulos -->
            <article class="card">
                <h3>Mis Módulos</h3>
                <ul class="modules-list">
                    @foreach($modules as $module)
                        <li>
                            {{ $module->name }}
                            @if($module->pending_tasks > 0)
                                <span class="text-sm bg-accent-color text-white px-2 py-1 rounded-full ml-2">
                                    {{ $module->pending_tasks }} tareas
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </article>

            <!-- Calendario -->
            <article class="card">
                <h3>Calendario</h3>
                <table class="calendar">
                    <thead>
                        <tr>
                            <th>D</th>
                            <th>L</th>
                            <th>M</th>
                            <th>X</th>
                            <th>J</th>
                            <th>V</th>
                            <th>S</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí iría el calendario dinámico -->
                    </tbody>
                </table>
            </article>

            <!-- Eventos -->
            <article class="card">
                <h3>Próximos Eventos</h3>
                <ul class="events-list">
                    @foreach($upcoming_events as $event)
                        <li>
                            {{ $event->title }}
                            <span>{{ $event->date->format('d M') }}</span>
                        </li>
                    @endforeach
                </ul>
            </article>

            <!-- Progreso -->
            <article class="card progress-circle" style="--progress:{{ $overall_progress }}%;">
                <svg viewBox="0 0 120 120">
                    <circle class="progress-bg" cx="60" cy="60" r="54"></circle>
                    <circle class="progress-bar" cx="60" cy="60" r="54"></circle>
                </svg>
                <div class="progress-text">{{ $overall_progress }}%</div>
                <h3 class="text-center mt-4">Progreso Académico</h3>
            </article>

            <!-- Mensajes -->
            <article class="card">
                <h3>Mensajes Recientes</h3>
                <ul class="messages-list">
                    @foreach($notifications as $notification)
                        <li>
                            <strong>{{ $notification->sender->name }}:</strong>
                            {{ $notification->message }}
                        </li>
                    @endforeach
                </ul>
            </article>

            <!-- Noticias -->
            <article class="card">
                <h3>Noticias</h3>
                <ul class="news-list">
                    @foreach($news as $newsItem)
                        <li>
                            <a href="{{ $newsItem->url }}" class="text-primary-color hover:text-primary-color-dark">
                                {{ $newsItem->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </article>

            <!-- Clases Grabadas -->
            <article class="card">
                <h3>Clases Grabadas</h3>
                <ul class="recorded-classes-list">
                    @foreach($recorded_classes as $class)
                        <li>
                            <a href="{{ route('recorded-class.show', $class->id) }}" class="text-primary-color hover:text-primary-color-dark">
                                {{ $class->module->name }} - {{ $class->created_at->format('d M Y') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </article>
        </section>
    </main>
</div>

@push('styles')
<style>
    /* Reset básico */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
    }

    /* Variables de color */
    :root {
        --primary-color: #0d47a1;
        --primary-light: #e3f2fd;
        --primary-dark: #0d47a1;
        --accent-color: #ffca28;
        --text-color: #222;
        --text-light: #555;
        --bg-light: #f5f7fa;
        --border-color: #eee;
    }

    body {
        background: var(--bg-light);
        color: var(--text-color);
    }

    /* Contenedor principal */
    .dashboard {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 220px;
        background: var(--primary-color);
        color: white;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-header {
        margin-bottom: 30px;
    }
    
    .sidebar-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .user-info img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .user-info h3 {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .sidebar-nav a i {
        width: 20px;
        text-align: center;
    }
    
    .sidebar-nav a:hover {
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .sidebar-nav a.active {
        background: var(--accent-color);
        color: var(--primary-color);
    }

    /* Contenido principal */
    .main-content {
        flex-grow: 1;
        padding: 30px 40px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Perfil */
    .profile {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .profile img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 2px solid var(--primary-color);
    }
    
    .profile-info h2 {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-color);
    }
    
    .profile-info p {
        color: var(--text-light);
        font-size: 0.9rem;
    }

    /* Grid general para tarjetas */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }

    /* Tarjetas */
    .card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .card-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-color);
    }
    
    .card-header i {
        font-size: 1.2rem;
        color: var(--primary-color);
    }

    /* Módulos/Asignaturas */
    .modules-list {
        list-style: none;
    }
    
    .modules-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        background: var(--primary-light);
        border-radius: 8px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .modules-list li:hover {
        background: rgba(13, 71, 161, 0.1);
    }
    
    .pending-tasks {
        background: var(--primary-color);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
    }

    /* Progress bar circular */
    .progress-circle {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    
    .progress-circle svg {
        transform: rotate(-90deg);
        width: 120px;
        height: 120px;
    }
    
    .progress-circle circle {
        fill: none;
        stroke-width: 10;
        stroke-linecap: round;
    }
    
    .progress-bg {
        stroke: var(--border-color);
    }
    
    .progress-bar {
        stroke: var(--primary-color);
        stroke-dasharray: 339.292;
        stroke-dashoffset: calc(339.292 * (1 - var(--progress)));
        transition: stroke-dashoffset 1s ease;
    }
    
    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
        transform: translate(-50%, -50%);
    }

    /* Mensajes */
    .messages-list {
        list-style: none;
        max-height: 200px;
        overflow-y: auto;
    }
    
    .messages-list li {
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .messages-list li strong {
        color: var(--primary-color);
    }

    /* Noticias */
    .news-list {
        list-style: none;
    }
    
    .news-list li {
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .news-list a {
        color: var(--primary-color);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .news-list a:hover {
        color: var(--primary-dark);
    }
    
    .news-list i {
        font-size: 0.8rem;
        color: var(--primary-color);
    }

    /* Clases Grabadas */
    .recorded-classes-list {
        list-style: none;
    }
    
    .recorded-classes-list li {
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .recorded-classes-list a {
        color: var(--text-color);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .recorded-classes-list a:hover {
        color: var(--primary-color);
    }
    
    .recorded-classes-list i {
        font-size: 1.1rem;
        color: var(--primary-color);
    }

    /* Responsive */
    @media(max-width: 650px) {
        .profile {
            flex-direction: column;
            align-items: center;
        }
        
        .profile-info {
            text-align: center;
        }
        
        .grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Inicializar el calendario
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const calendar = document.querySelector('.calendar tbody');
        
        // Llenar el calendario con días del mes actual
        const year = today.getFullYear();
        const month = today.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const dayOfWeek = firstDay.getDay();

        // Crear la primera fila con los días anteriores
        let row = document.createElement('tr');
        for (let i = 0; i < dayOfWeek; i++) {
            const cell = document.createElement('td');
            row.appendChild(cell);
        }
        calendar.appendChild(row);

        // Llenar el resto del calendario
        let currentDay = 1;
        let currentRow = row;
        
        while (currentDay <= daysInMonth) {
            if (!currentRow) {
                currentRow = document.createElement('tr');
                calendar.appendChild(currentRow);
            }
            
            const cell = document.createElement('td');
            cell.textContent = currentDay;
            cell.className = 'calendar-day';
            
            // Marcar el día actual
            if (currentDay === today.getDate() && month === today.getMonth()) {
                cell.className += ' today';
            }
            
            currentRow.appendChild(cell);
            currentDay++;
            
            // Si la fila tiene 7 días, crear una nueva
            if (currentRow.children.length === 7) {
                currentRow = null;
            }
        }
    });

    // Añadir estilos dinámicos para el calendario
    const style = document.createElement('style');
    style.textContent = `
        .calendar-day {
            padding: 10px;
            text-align: center;
            color: #666;
        }
        .calendar-day.today {
            background-color: #0d47a1;
            color: white;
            border-radius: 50%;
        }
        .calendar-day:hover {
            background-color: #e3f2fd;
            cursor: pointer;
        }
    `;
    document.head.appendChild(style);
</script>
@endpush

@endsection
                        <p class="text-sm text-gray-600">
                            Lunes a Viernes: 10:00 - 14:00 y 16:00 - 18:00
                        </p>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-2">Mensajes</h4>
                        <div class="space-y-2">
                            <button class="w-full text-left text-blue-600 hover:text-blue-800">
                                <i class="fas fa-envelope mr-2"></i> Enviar Mensaje
                            </button>
                            <button class="w-full text-left text-green-600 hover:text-green-800">
                                <i class="fas fa-video mr-2"></i> Videoconferencia
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-2">Solicitar Cita</h4>
                        <form class="space-y-2">
                            <input type="date" class="w-full border rounded-lg px-3 py-1">
                            <input type="time" class="w-full border rounded-lg px-3 py-1">
                            <textarea placeholder="Motivo de la cita..." 
                                      class="w-full border rounded-lg px-3 py-1"></textarea>
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700">
                                Solicitar Cita
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servicios Adicionales -->
        <div class="col-span-12 md:col-span-4">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-semibold mb-4">Servicios Adicionales</h3>
                
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-2">Portal de Empleo</h4>
                        <a href="{{ route('job.portal') }}" 
                           class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-briefcase mr-2"></i> Ver Ofertas de Trabajo
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel principal -->
        <div class="col-span-12 md:col-span-8">
            <!-- Calendario -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Calendario</h3>
                    <div class="flex items-center space-x-4">
                        <select class="border rounded-lg px-3 py-1 text-sm">
                            <option value="month">Mes</option>
                            <option value="week">Semana</option>
                            <option value="day">Día</option>
                        </select>
                        <button class="border rounded-lg px-3 py-1 text-sm">
                            <i class="fas fa-plus mr-2"></i> Nuevo evento
                        </button>
                    </div>
                </div>
                
                <div id="calendar" class="w-full h-96 bg-gray-50 rounded-lg"></div>
            </div>

            <!-- Tareas Pendientes -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">Tareas Pendientes</h3>
                    <select class="border rounded-lg px-3 py-1 text-sm">
                        <option value="all">Todas las asignaturas</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-4">
                    @foreach($pendingTasks as $task)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium">{{ $task['title'] }}</h4>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $task['due_date']->format('d/m/Y') }}
                                    </p>
                                    
                                    @if($task['status'] === 'pending')
                                        <span class="inline-block px-2 py-1 text-xs text-red-600 bg-red-100 rounded-full">
                                            Pendiente
                                        </span>
                                    @elseif($task['status'] === 'delivered')
                                        <span class="inline-block px-2 py-1 text-xs text-yellow-600 bg-yellow-100 rounded-full">
                                            Entregado
                                        </span>
                                    @elseif($task['status'] === 'graded')
                                        <span class="inline-block px-2 py-1 text-xs text-green-600 bg-green-100 rounded-full">
                                            Corregido
                                        </span>
                                    @endif

                                    @if($task['grade'])
                                        <span class="ml-2 inline-block px-2 py-1 text-xs text-blue-600 bg-blue-100 rounded-full">
                                            {{ $task['grade'] }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex flex-col items-end">
                                    <a href="{{ route('task.show', $task) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye mr-2"></i> Ver tarea
                                    </a>
                                    
                                    @if($task['status'] === 'pending')
                                        <a href="{{ route('task.submit', $task) }}" 
                                           class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-upload mr-2"></i> Entregar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            transition: all 0.2s;
            color: #4b5563;
            margin-bottom: 0.5rem;
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

        /* Estilos para el gráfico de progreso */
        .chart-container {
            position: relative;
            width: 100%;
            height: 200px;
        }
        .chart-circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(
                #3b82f6 {{ $average * 3.6 }}deg,
                #e5e7eb {{ $average * 3.6 }}deg
            );
        }
        .chart-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            height: 60%;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        </div>

        <!-- Progreso Académico -->
        <div class="col-span-12 md:col-span-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-semibold mb-4">Progreso Académico</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gráfico de Progreso -->
                    <div class="chart-container">
                        <div class="chart-circle"></div>
                        <div class="chart-center">{{ number_format($average, 1) }}%</div>
                    </div>
                    
                    <!-- Detalles de Módulos -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-medium">Notas por Módulo</h4>
                                <p class="text-sm text-gray-500">Promedio general</p>
                            </div>
                            <span class="text-2xl font-bold">{{ number_format($average, 1) }}</span>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($module_grades as $module => $grade)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="text-sm">{{ $module }}</h5>
                                        <p class="text-xs text-gray-500">{{ $grade }} pts</p>
                                    </div>
                                    <div class="w-32 bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" 
                                             style="width: {{ $grade * 10 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Funciones para manejar interacciones
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar sincronización con Google Calendar
            const syncBtn = document.querySelector('.sync-calendar');
            if (syncBtn) {
                syncBtn.addEventListener('click', function() {
                    if (!confirm('¿Deseas sincronizar tu calendario con Google Calendar?')) {
                        return false;
                    }
                });
            }
        });
    </script>
</div>


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
                    <!-- Panel de Perfil -->
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/default-avatar.png') }}" 
                                     alt="{{ $user->name }}"
                                     class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                                <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                                <p class="text-gray-600">{{ $user->course }} - {{ $user->cycle }}</p>
                            </div>
                            <div class="space-y-4">
                                <a href="{{ route('profile.edit') }}" class="block text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-user-edit mr-2"></i> Editar Perfil
                                </a>
                                <a href="{{ route('settings') }}" class="block text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-cog mr-2"></i> Configuración
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-red-600 hover:text-red-800">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                            <div class="space-y-4">
                                <h4 class="font-semibold">Progreso Académico</h4>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $average }}%"></div>
                                </div>
                                <p class="text-sm text-gray-600">Media: {{ number_format($average, 1) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Calendario de Actividades -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold">Calendario de Actividades</h3>
                            <div class="flex items-center space-x-4">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-2">
                            <div>Lun</div>
                            <div>Mar</div>
                            <div>Mié</div>
                            <div>Jue</div>
                            <div>Vie</div>
                            <div>Sáb</div>
                            <div>Dom</div>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-1 text-center text-sm">
                            @foreach($calendar as $day)
                                <div class="calendar-cell {{ $day['active'] ? 'bg-blue-600 text-white' : '' }} 
                                      {{ $day['has_event'] ? 'border-blue-600 border' : '' }}">
                                    {{ $day['number'] }}
                                    @if(count($day['events']) > 0)
                                        <div class="mt-1">
                                            @foreach($day['events'] as $event)
                                                <span class="text-xs {{ ($event['type'] === 'class' ? 'text-blue-600' : 
                                                                ($event['type'] === 'exam' ? 'text-red-600' : 'text-green-600')) }}">
                                                    {{ $event['title'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
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
                    <!-- Tareas y Trabajos -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold">Tareas y Trabajos</h3>
                            <select class="border rounded-lg px-3 py-1 text-sm">
                                <option value="all">Todas las asignaturas</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($pendingTasks as $task)
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium">{{ $task['title'] }}</h4>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-2"></i>
                                                {{ $task['due_date']->format('d/m/Y') }}
                                            </p>
                                            
                                            @if($task['status'] === 'pending')
                                                <span class="inline-block px-2 py-1 text-xs text-red-600 bg-red-100 rounded-full">
                                                    Pendiente
                                                </span>
                                            @elseif($task['status'] === 'delivered')
                                                <span class="inline-block px-2 py-1 text-xs text-yellow-600 bg-yellow-100 rounded-full">
                                                    Entregado
                                                </span>
                                            @elseif($task['status'] === 'graded')
                                                <span class="inline-block px-2 py-1 text-xs text-green-600 bg-green-100 rounded-full">
                                                    Corregido
                                                </span>
                                            @endif

                                            @if($task['grade'])
                                                <span class="ml-2 inline-block px-2 py-1 text-xs text-blue-600 bg-blue-100 rounded-full">
                                                    {{ $task['grade'] }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex flex-col items-end">
                                            <a href="{{ route('task.show', $task) }}" 
                                               class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye mr-2"></i> Ver tarea
                                            </a>
                                            
                                            @if($task['status'] === 'pending')
                                                <a href="{{ route('task.submit', $task) }}" 
                                                   class="text-green-600 hover:text-green-800">
                                                    <i class="fas fa-upload mr-2"></i> Entregar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Módulos -->
                    <div class="dashboard-card mt-6">
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
