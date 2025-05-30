<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MentorHub') }} - Dashboard de Estudiante</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased bg-light">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="MentorHub" class="sidebar-logo">
                </a>
                <button type="button" id="sidebarCollapse" class="btn d-md-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="sidebar-user">
                <div class="sidebar-user-info">
                    @if(auth()->user()->profile && auth()->user()->profile->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="{{ auth()->user()->name }}" class="sidebar-avatar">
                    @else
                        <div class="sidebar-avatar-placeholder">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="sidebar-user-details">
                        <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                        <span class="badge bg-primary mt-1">Estudiante</span>
                    </div>
                </div>
            </div>

            <ul class="list-unstyled sidebar-menu">
                <li class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('student.dashboard') }}">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="{{ request()->routeIs('student.courses*') ? 'active' : '' }}">
                    <a href="{{ route('student.courses') }}">
                        <i class="fas fa-book"></i> Mis Cursos
                    </a>
                </li>
                <li class="{{ request()->routeIs('student.tasks*') ? 'active' : '' }}">
                    <a href="{{ route('student.tasks') }}">
                        <i class="fas fa-tasks"></i> Tareas
                    </a>
                </li>
                <li class="{{ request()->routeIs('student.calendar*') ? 'active' : '' }}">
                    <a href="{{ route('student.calendar') }}">
                        <i class="fas fa-calendar-alt"></i> Calendario
                    </a>
                </li>
                <li class="{{ request()->routeIs('student.messages*') ? 'active' : '' }}">
                    <a href="{{ route('student.messages.index') }}">
                        <i class="fas fa-envelope"></i> Mensajes
                        @if(auth()->user()->unreadMessages()->count() > 0)
                            <span class="badge bg-danger float-end">{{ auth()->user()->unreadMessages()->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="{{ request()->routeIs('student.courses.*.progress*') ? 'active' : '' }}">
                    <a href="{{ route('student.courses.index') }}">
                        <i class="fas fa-chart-line"></i> Progreso
                    </a>
                </li>
                <li class="{{ request()->routeIs('student.profile*') ? 'active' : '' }}">
                    <a href="{{ route('student.profile') }}">
                        <i class="fas fa-user"></i> Mi Perfil
                    </a>
                </li>
                <li>
                    <a href="{{ route('courses.index') }}">
                        <i class="fas fa-search"></i> Explorar Cursos
                    </a>
                </li>
                <li class="sidebar-divider"></li>
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>

            <div class="sidebar-footer">
                <p class="small text-muted mb-0">&copy; {{ date('Y') }} MentorHub</p>
                <p class="small text-muted">Todos los derechos reservados</p>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content" class="dashboard-content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapseBtn" class="btn d-none d-md-block">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a id="notificationsDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="badge rounded-pill bg-danger">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end notifications-dropdown" aria-labelledby="notificationsDropdown">
                                    <div class="notifications-header">
                                        <span>Notificaciones</span>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <a href="{{ route('student.notifications.mark-all-read') }}" class="text-primary small">Marcar todas como leídas</a>
                                        @endif
                                    </div>
                                    <div class="notifications-body">
                                        @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                            <a href="{{ route('student.notifications.show', $notification) }}" class="dropdown-item notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                                <div class="notification-icon">
                                                    @if($notification->type == 'App\Notifications\CourseEnrollment')
                                                        <i class="fas fa-book-open text-primary"></i>
                                                    @elseif($notification->type == 'App\Notifications\NewCourseContent')
                                                        <i class="fas fa-plus-circle text-success"></i>
                                                    @elseif($notification->type == 'App\Notifications\CourseCompleted')
                                                        <i class="fas fa-trophy text-warning"></i>
                                                    @else
                                                        <i class="fas fa-bell text-info"></i>
                                                    @endif
                                                </div>
                                                <div class="notification-content">
                                                    <p class="mb-0">{{ Str::limit($notification->data['message'], 50) }}</p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="dropdown-item notification-empty">
                                                <p class="mb-0 text-muted">No tienes notificaciones</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="notifications-footer">
                                        <a href="{{ route('student.notifications') }}" class="dropdown-item text-center small text-primary">
                                            Ver todas las notificaciones
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="userDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @if(auth()->user()->profile && auth()->user()->profile->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="{{ auth()->user()->name }}" class="navbar-avatar">
                                    @else
                                        <span class="navbar-avatar-placeholder">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    @endif
                                    <span class="d-none d-md-inline-block ms-1">{{ auth()->user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ route('student.profile') }}">
                                        <i class="fas fa-user me-2"></i> Mi Perfil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('student.settings') }}">
                                        <i class="fas fa-cog me-2"></i> Configuración
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                    </a>
                                    <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="py-3">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="container-fluid">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="container-fluid">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="container-fluid">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="container-fluid">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <!-- Dashboard Content -->
                @yield('dashboard-content')
            </main>

            <!-- Footer -->
            <footer class="dashboard-footer bg-white py-3 border-top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted">&copy; {{ date('Y') }} MentorHub. Todos los derechos reservados.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="{{ route('privacy.policy') }}" class="text-muted me-3">Política de privacidad</a>
                            <a href="{{ route('terms') }}" class="text-muted me-3">Términos de servicio</a>
                            <a href="{{ route('contact') }}" class="text-muted">Contacto</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            }

            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', toggleSidebar);
            }

            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
