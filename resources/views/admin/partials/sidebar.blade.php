<div class="sidebar bg-dark text-white" id="sidebar">
    <div class="sidebar-header p-3 border-bottom">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="MentorHub" class="me-2" height="30">
            <h5 class="mb-0 text-white">MentorHub Admin</h5>
        </div>
    </div>
    
    <div class="sidebar-body p-0">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <!-- Usuarios -->
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                
                <!-- Roles y Permisos -->
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield me-2"></i>
                        <p>
                            Roles y Permisos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ms-3">
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') && !request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                <i class="fas fa-user-tag me-2"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                <i class="fas fa-key me-2"></i>
                                <p>Permisos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Cursos -->
                <li class="nav-item">
                    <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap me-2"></i>
                        <p>Cursos</p>
                    </a>
                </li>
                
                <!-- Eventos -->
                <li class="nav-item">
                    <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <p>Eventos</p>
                    </a>
                </li>
                
                <!-- Comunicaciones -->
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.notifications.*') || request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                        <i class="fas fa-comments me-2"></i>
                        <p>
                            Comunicaciones
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ms-3">
                        <li class="nav-item">
                            <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                                <i class="fas fa-bell me-2"></i>
                                <p>Notificaciones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                                <i class="fas fa-envelope me-2"></i>
                                <p>Mensajes</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Registros de Actividad -->
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.activity_logs.*') ? 'active' : '' }}">
                        <i class="fas fa-history me-2"></i>
                        <p>
                            Registros de Actividad
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ms-3">
                        <li class="nav-item">
                            <a href="{{ route('admin.activity_logs.index') }}" class="nav-link {{ request()->routeIs('admin.activity_logs.index') || request()->routeIs('admin.activity_logs.show') ? 'active' : '' }}">
                                <i class="fas fa-list me-2"></i>
                                <p>Historial</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.activity_logs.analytics') }}" class="nav-link {{ request()->routeIs('admin.activity_logs.analytics') ? 'active' : '' }}">
                                <i class="fas fa-chart-line me-2"></i>
                                <p>Panel de Análisis</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Configuración -->
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog me-2"></i>
                        <p>Configuración</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Expandir/colapsar submenús
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const subMenu = item.querySelector('.nav-treeview');
            
            if (link && subMenu) {
                if (link.classList.contains('active')) {
                    subMenu.style.display = 'block';
                } else {
                    subMenu.style.display = 'none';
                }
                
                link.addEventListener('click', function(e) {
                    if (subMenu) {
                        e.preventDefault();
                        if (subMenu.style.display === 'none') {
                            subMenu.style.display = 'block';
                        } else {
                            subMenu.style.display = 'none';
                        }
                    }
                });
            }
        });
    });
</script>

<style>
    .sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 100;
        transition: all 0.3s;
        overflow-y: auto;
    }
    
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }
    
    .sidebar .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar .nav-link.active {
        color: #fff;
        background: #007bff;
    }
    
    .sidebar .nav-link p {
        margin-bottom: 0;
        flex-grow: 1;
    }
    
    .sidebar .nav-treeview {
        margin-left: 10px;
        border-left: 1px solid rgba(255, 255, 255, 0.2);
        padding-left: 5px;
    }
</style>
