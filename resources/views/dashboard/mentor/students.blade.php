@extends('layouts.mentor')

@section('title', 'Gestión de Estudiantes - MentorHub')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Estudiantes</h1>
        <div>
            <a href="#" class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#filterStudentsModal">
                <i class="fas fa-filter fa-sm text-white-50"></i> Filtrar
            </a>
        </div>
    </div>
    
    @include('partials.alerts')
    
    <!-- Estadísticas de estudiantes -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Estudiantes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Estudiantes Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Promedio de Sesiones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">4.2</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Solicitudes Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenido principal -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Estudiantes</h6>
                    <div class="input-group w-50">
                        <input type="text" class="form-control" id="searchStudent" placeholder="Buscar estudiante...">
                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="studentsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Curso</th>
                                    <th>Sesiones</th>
                                    <th>Progreso</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos de ejemplo -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/img/undraw_profile.svg" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div>María García</div>
                                                <div class="small text-muted">Desde: 15/04/2025</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>maria.garcia@example.com</td>
                                    <td>Desarrollo Web Frontend</td>
                                    <td>5 <small class="text-muted">(3 completadas)</small></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted">75% completado</small>
                                    </td>
                                    <td><span class="badge bg-success">Activo</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver perfil">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Programar sesión">
                                                <i class="fas fa-calendar-plus"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Enviar mensaje">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/img/undraw_profile_2.svg" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div>Carlos Rodríguez</div>
                                                <div class="small text-muted">Desde: 22/03/2025</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>carlos.rodriguez@example.com</td>
                                    <td>JavaScript Avanzado</td>
                                    <td>8 <small class="text-muted">(6 completadas)</small></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted">60% completado</small>
                                    </td>
                                    <td><span class="badge bg-success">Activo</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver perfil">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Programar sesión">
                                                <i class="fas fa-calendar-plus"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Enviar mensaje">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/img/undraw_profile_3.svg" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div>Ana Martínez</div>
                                                <div class="small text-muted">Desde: 10/05/2025</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>ana.martinez@example.com</td>
                                    <td>Base de Datos SQL</td>
                                    <td>3 <small class="text-muted">(3 completadas)</small></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted">90% completado</small>
                                    </td>
                                    <td><span class="badge bg-success">Activo</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver perfil">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Programar sesión">
                                                <i class="fas fa-calendar-plus"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Enviar mensaje">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/img/undraw_profile_1.svg" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div>Pedro López</div>
                                                <div class="small text-muted">Desde: 05/02/2025</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>pedro.lopez@example.com</td>
                                    <td>Python para Data Science</td>
                                    <td>2 <small class="text-muted">(1 completada)</small></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted">30% completado</small>
                                    </td>
                                    <td><span class="badge bg-warning">Inactivo</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver perfil">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Programar sesión">
                                                <i class="fas fa-calendar-plus"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Enviar mensaje">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="/img/undraw_profile_2.svg" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div>Laura Sánchez</div>
                                                <div class="small text-muted">Desde: 20/05/2025</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>laura.sanchez@example.com</td>
                                    <td>React Fundamentals</td>
                                    <td>1 <small class="text-muted">(0 completadas)</small></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted">10% completado</small>
                                    </td>
                                    <td><span class="badge bg-success">Activo</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver perfil">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Programar sesión">
                                                <i class="fas fa-calendar-plus"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Enviar mensaje">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">Mostrando 5 de 12 estudiantes</div>
                        <nav>
                            <ul class="pagination pagination-sm">
                                <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para filtrar estudiantes -->
<div class="modal fade" id="filterStudentsModal" tabindex="-1" aria-labelledby="filterStudentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterStudentsModalLabel">Filtrar Estudiantes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="active" id="filter-active" checked>
                            <label class="form-check-label" for="filter-active">
                                Activos
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="inactive" id="filter-inactive">
                            <label class="form-check-label" for="filter-inactive">
                                Inactivos
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filter-course" class="form-label">Curso</label>
                        <select class="form-select" id="filter-course">
                            <option value="">Todos los cursos</option>
                            <option value="1">Desarrollo Web Frontend</option>
                            <option value="2">JavaScript Avanzado</option>
                            <option value="3">Base de Datos SQL</option>
                            <option value="4">Python para Data Science</option>
                            <option value="5">React Fundamentals</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filter-progress" class="form-label">Progreso mínimo</label>
                        <div class="d-flex align-items-center">
                            <input type="range" class="form-range flex-grow-1 me-2" id="filter-progress" min="0" max="100" step="10" value="0">
                            <span id="progress-value">0%</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filter-sessions" class="form-label">Número mínimo de sesiones</label>
                        <input type="number" class="form-control" id="filter-sessions" min="0" value="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="applyFiltersBtn">Aplicar Filtros</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver perfil de estudiante -->
<div class="modal fade" id="studentProfileModal" tabindex="-1" aria-labelledby="studentProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentProfileModalLabel">Perfil del Estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <img src="/img/undraw_profile.svg" class="rounded-circle mb-3" width="120" height="120" id="student-profile-img">
                        <h4 id="student-name">Nombre del Estudiante</h4>
                        <p class="text-muted" id="student-email">email@example.com</p>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-sm btn-outline-primary" id="schedule-session-btn">
                                <i class="fas fa-calendar-plus me-1"></i> Programar Sesión
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-success" id="send-message-btn">
                                <i class="fas fa-envelope me-1"></i> Enviar Mensaje
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav nav-tabs" id="studentProfileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Información</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sessions-tab" data-bs-toggle="tab" data-bs-target="#sessions" type="button" role="tab" aria-controls="sessions" aria-selected="false">Sesiones</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab" aria-controls="progress" aria-selected="false">Progreso</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab" aria-controls="notes" aria-selected="false">Notas</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-3" id="studentProfileTabsContent">
                            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                <div class="mb-3">
                                    <h6>Información Personal</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Teléfono:</strong> <span id="student-phone">+34 612 345 678</span></p>
                                            <p><strong>Ubicación:</strong> <span id="student-location">Madrid, España</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Fecha de registro:</strong> <span id="student-registered">15/04/2025</span></p>
                                            <p><strong>Zona horaria:</strong> <span id="student-timezone">Europe/Madrid (UTC+2)</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6>Cursos</h6>
                                    <ul class="list-group list-group-flush" id="student-courses">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Desarrollo Web Frontend
                                            <span class="badge bg-success rounded-pill">75%</span>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <h6>Intereses y Objetivos</h6>
                                    <p id="student-interests">Desarrollo web, diseño UI/UX, y aplicaciones móviles.</p>
                                    <p><strong>Objetivo:</strong> <span id="student-goals">Convertirse en desarrollador frontend en 6 meses.</span></p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sessions" role="tabpanel" aria-labelledby="sessions-tab">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Tema</th>
                                                <th>Estado</th>
                                                <th>Evaluación</th>
                                            </tr>
                                        </thead>
                                        <tbody id="student-sessions-table">
                                            <tr>
                                                <td>28/05/2025 10:00</td>
                                                <td>Introducción a HTML y CSS</td>
                                                <td><span class="badge bg-success">Completada</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-secondary"></i>
                                                        </div>
                                                        <span>4.0</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>21/05/2025 11:00</td>
                                                <td>Flexbox y Grid</td>
                                                <td><span class="badge bg-success">Completada</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                        </div>
                                                        <span>5.0</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>14/05/2025 10:00</td>
                                                <td>Introducción a JavaScript</td>
                                                <td><span class="badge bg-success">Completada</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-secondary"></i>
                                                        </div>
                                                        <span>4.0</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                                <div class="mb-4">
                                    <h6>Progreso General</h6>
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                    </div>
                                </div>
                                <div>
                                    <h6>Progreso por Módulos</h6>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>HTML y CSS Básico</span>
                                            <span>100%</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>JavaScript Fundamentals</span>
                                            <span>80%</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Frameworks Frontend</span>
                                            <span>50%</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Proyecto Final</span>
                                            <span>10%</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                <div class="mb-3">
                                    <label for="student-notes" class="form-label">Notas sobre el estudiante</label>
                                    <textarea class="form-control" id="student-notes" rows="5" placeholder="Añade notas sobre este estudiante...">Estudiante muy comprometido. Tiene dificultades con los conceptos de JavaScript asíncrono. Recomendado repasar promesas y async/await en la próxima sesión.</textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary" id="save-notes-btn">Guardar Notas</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Manejar la barra de progreso en el modal de filtros
        const progressRange = document.getElementById('filter-progress');
        const progressValue = document.getElementById('progress-value');
        
        progressRange.addEventListener('input', function() {
            progressValue.textContent = this.value + '%';
        });
        
        // Manejar el botón de aplicar filtros
        document.getElementById('applyFiltersBtn').addEventListener('click', function() {
            const activeFilter = document.getElementById('filter-active').checked;
            const inactiveFilter = document.getElementById('filter-inactive').checked;
            const courseFilter = document.getElementById('filter-course').value;
            const progressFilter = document.getElementById('filter-progress').value;
            const sessionsFilter = document.getElementById('filter-sessions').value;
            
            // Aquí iría la lógica para filtrar la tabla de estudiantes
            console.log('Filtros aplicados:', {
                active: activeFilter,
                inactive: inactiveFilter,
                course: courseFilter,
                progress: progressFilter,
                sessions: sessionsFilter
            });
            
            // Cerrar el modal
            bootstrap.Modal.getInstance(document.getElementById('filterStudentsModal')).hide();
            
            // Mostrar mensaje de éxito
            alert('Filtros aplicados correctamente');
        });
        
        // Manejar la búsqueda de estudiantes
        document.getElementById('searchBtn').addEventListener('click', function() {
            const searchTerm = document.getElementById('searchStudent').value.toLowerCase();
            
            // Aquí iría la lógica para buscar estudiantes
            console.log('Buscando:', searchTerm);
            
            // Ejemplo simple de filtrado en el cliente
            const rows = document.querySelectorAll('#studentsTable tbody tr');
            
            rows.forEach(row => {
                const name = row.querySelector('td:first-child').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const course = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm) || course.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Manejar el evento de tecla Enter en el campo de búsqueda
        document.getElementById('searchStudent').addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });
        
        // Manejar clic en botón de ver perfil
        const profileButtons = document.querySelectorAll('.btn-outline-primary');
        profileButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Obtener datos del estudiante (en una aplicación real, estos datos vendrían del servidor)
                const row = this.closest('tr');
                const name = row.querySelector('td:first-child div div:first-child').textContent;
                const email = row.querySelector('td:nth-child(2)').textContent;
                const course = row.querySelector('td:nth-child(3)').textContent;
                
                // Actualizar el modal con los datos del estudiante
                document.getElementById('student-name').textContent = name;
                document.getElementById('student-email').textContent = email;
                document.getElementById('studentProfileModalLabel').textContent = 'Perfil de ' + name;
                
                // Mostrar el modal
                const profileModal = new bootstrap.Modal(document.getElementById('studentProfileModal'));
                profileModal.show();
            });
        });
        
        // Manejar clic en guardar notas
        document.getElementById('save-notes-btn').addEventListener('click', function() {
            const notes = document.getElementById('student-notes').value;
            
            // Aquí iría la lógica para guardar las notas en el servidor
            console.log('Guardando notas:', notes);
            
            // Mostrar mensaje de éxito
            alert('Notas guardadas correctamente');
        });
    });
</script>
@endpush
