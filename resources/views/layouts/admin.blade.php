<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - MentorHub Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">

    @stack('styles')
</head>
<body data-theme="{{ session('theme', 'light') }}" id="theme-container" class="theme-{{ session('theme', 'light') }}">
    <!-- Theme styles -->
    <style>
        :root {
            --theme-primary: {{ session('theme', 'light') === 'dark' ? '#5a7bfc' : '#4361ee' }};
            --theme-bg: {{ session('theme', 'light') === 'dark' ? '#1a1a1a' : '#ffffff' }};
            --theme-text: {{ session('theme', 'light') === 'dark' ? '#f8f9fa' : '#212529' }};
            --theme-border: {{ session('theme', 'light') === 'dark' ? '#495057' : '#dee2e6' }};
        }
        
        body {
            background-color: var(--theme-bg);
            color: var(--theme-text);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="MentorHub" class="img-fluid" style="max-height: 40px;">
                    <span class="align-middle">MentorHub</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-header">Principal</li>
                    <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-header">Gestión de Usuarios</li>
                    <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i> <span>Usuarios</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.mentors.pending') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.mentors.pending') }}">
                            <i class="fas fa-user-clock"></i> <span>Solicitudes de Mentores</span>
                            @php
                                $pendingCount = \App\Models\User::role('mentor')->where('is_active', false)->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="badge bg-danger rounded-pill ms-2">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.roles.index') }}">
                            <i class="fas fa-user-tag"></i> <span>Roles y Permisos</span>
                        </a>
                    </li>

                    <li class="sidebar-header">Contenido Educativo</li>
                    <li class="sidebar-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.courses.index') }}">
                            <i class="fas fa-book"></i> <span>Cursos</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.courses.index') }}">
                            <i class="fas fa-cubes"></i> <span>Módulos</span>
                        </a>
                    </li>

                    <li class="sidebar-header">Eventos y Calendario</li>
                    <li class="sidebar-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.events.index') }}">
                            <i class="fas fa-calendar-alt"></i> <span>Eventos</span>
                        </a>
                    </li>

                    <li class="sidebar-header">Comunicaciones</li>
                    <li class="sidebar-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.notifications.index') }}">
                            <i class="fas fa-bell"></i> <span>Notificaciones</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.messages.index') }}">
                            <i class="fas fa-envelope"></i> <span>Mensajes</span>
                        </a>
                    </li>

                    <li class="sidebar-header">Sistema</li>
                    <li class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-cogs"></i> <span>Configuración</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-history"></i> <span>Registro de Actividad</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main">
            <!-- Navbar -->
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="fas fa-bell align-middle"></i>
                                    <span class="indicator">4</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                                <div class="dropdown-menu-header">
                                    4 Notificaciones Nuevas
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="fas fa-user-plus text-success"></i>
                                            </div>
                                            <div class="col-10">
                                                <div>Nuevo usuario registrado</div>
                                                <div class="text-muted small mt-1">Hace 15 minutos</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Mostrar todas las notificaciones</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="fas fa-envelope align-middle"></i>
                                    <span class="indicator">2</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
                                <div class="dropdown-menu-header">
                                    <div class="position-relative">
                                        2 Mensajes Nuevos
                                    </div>
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="{{ asset('img/avatars/avatar-1.jpg') }}" class="avatar img-fluid rounded-circle" alt="Usuario">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div>Juan Pérez</div>
                                                <div class="text-muted small mt-1">Consulta sobre el curso de programación</div>
                                                <div class="text-muted small mt-1">Hace 30m</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Mostrar todos los mensajes</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->profile_photo_url }}" class="avatar img-fluid rounded-circle me-1" alt="{{ Auth::user()->name }}" />
                                <span class="text-dark d-none d-sm-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.settings.appearance') }}">
                                        <i class="fas fa-paint-brush me-2"></i> Configuración de Tema
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-power-off me-2"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="#"><strong>MentorHub</strong></a> &copy; {{ date('Y') }}
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#">Soporte</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#">Ayuda</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#">Privacidad</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#">Términos</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Theme Script -->
    <script>
        // Apply saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for saved theme preference
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const sessionTheme = '{{ session('theme', 'system') }}';
            
            // Determine which theme to use (saved > session > system)
            let theme = savedTheme || sessionTheme;
            
            // If theme is set to 'system', use the system preference
            if (theme === 'system') {
                theme = systemTheme;
            }
            
            // Apply the theme
            applyTheme(theme);
            updateThemeSwitcher(theme);
            
            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (sessionTheme === 'system' || (!savedTheme && sessionTheme === 'system')) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    applyTheme(newTheme);
                }
            });
        });
        
        // Apply theme to the page
        function applyTheme(theme) {
            // Remove all theme classes
            document.body.classList.remove('theme-light', 'theme-dark');
            
            // Add the selected theme class
            document.body.classList.add(`theme-${theme}`);
            document.body.setAttribute('data-theme', theme);
            
            // Update theme in session via AJAX if it's different from the current session
            const currentTheme = '{{ session('theme', 'system') }}';
            if (theme !== currentTheme && theme !== 'system') {
                updateThemeOnServer(theme);
            }
            
            // Update theme switcher UI
            updateThemeSwitcher(theme);
        }
        
        // Update theme on the server via AJAX
        function updateThemeOnServer(theme) {
            fetch('{{ route('admin.settings.appearance.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    theme: theme,
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Theme updated successfully');
                }
            })
            .catch(error => {
                console.error('Error updating theme:', error);
            });
        }
        
        // Update theme switcher UI
        function updateThemeSwitcher(theme) {
            // Remove active class from all theme options
            document.querySelectorAll('.theme-option').forEach(option => {
                option.classList.remove('active');
            });
            
            // Add active class to the current theme option
            const currentTheme = theme === 'system' ? 'system' : theme;
            const activeOption = document.querySelector(`.theme-option[data-theme="${currentTheme}"]`);
            if (activeOption) {
                activeOption.classList.add('active');
            }
        }
        
        // Function to change theme
        function setTheme(theme) {
            // Save to localStorage for immediate feedback on next page load
            localStorage.setItem('theme', theme);
            
            // If theme is set to 'system', use the system preference
            if (theme === 'system') {
                const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                applyTheme(systemTheme);
            } else {
                applyTheme(theme);
            }
        }
    </script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/datatables-custom.js') }}"></script>
    @stack('scripts')
</body>
</html>