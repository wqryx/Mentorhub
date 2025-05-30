@extends('layouts.dashboard.student')

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mis Tareas</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary active" id="all-tasks-btn">
                    Todas
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="pending-tasks-btn">
                    Pendientes
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="completed-tasks-btn">
                    Completadas
                </button>
            </div>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fas fa-plus me-1"></i> Nueva tarea
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text" id="search-addon">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="task-search" placeholder="Buscar tareas..." aria-label="Buscar" aria-describedby="search-addon">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="course-filter">
                                <option value="all" selected>Todos los cursos</option>
                                <option value="1">Desarrollo Web Avanzado</option>
                                <option value="2">Fundamentos de Bases de Datos</option>
                                <option value="3">Algoritmos Avanzados</option>
                                <option value="4">Sistemas Operativos</option>
                                <option value="5">Introducción a la IA</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="priority-filter">
                                <option value="all" selected>Todas las prioridades</option>
                                <option value="high">Alta</option>
                                <option value="medium">Media</option>
                                <option value="low">Baja</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="due-date-filter">
                                <option value="all" selected>Todas las fechas</option>
                                <option value="today">Hoy</option>
                                <option value="tomorrow">Mañana</option>
                                <option value="week">Esta semana</option>
                                <option value="month">Este mes</option>
                                <option value="overdue">Atrasadas</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de tareas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tareas pendientes</h5>
                        <div>
                            <span class="badge bg-primary rounded-pill">12 tareas</span>
                            <div class="btn-group ms-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary view-mode-btn active" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary view-mode-btn" data-view="board">
                                    <i class="fas fa-th"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Vista de lista (por defecto) -->
                    <div id="list-view">
                        <div class="list-group list-group-flush task-list">
                            <!-- Tarea 1 - Alta prioridad, vencimiento cercano -->
                            <div class="list-group-item list-group-item-action task-item" data-priority="high" data-status="pending" data-course="1" data-due="2025-05-29">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input task-checkbox" type="checkbox" value="" id="task1">
                                        <label class="form-check-label" for="task1">
                                            <span class="task-title">Proyecto Final - Desarrollo Web</span>
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-danger me-2">Alta</span>
                                        <small class="text-danger me-3"><i class="fas fa-clock me-1"></i> Mañana</small>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i> Editar</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-check me-2"></i> Marcar como completada</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i> Eliminar</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="task-details mt-2">
                                    <p class="mb-1 text-muted">Completar el proyecto final para el curso de Desarrollo Web Avanzado, incluyendo documentación y tests.</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info text-dark me-2">Desarrollo Web Avanzado</span>
                                        <div class="progress flex-grow-1" style="height: 5px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ms-2 text-muted small">75%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tarea 2 - Media prioridad -->
                            <div class="list-group-item list-group-item-action task-item" data-priority="medium" data-status="pending" data-course="2" data-due="2025-06-02">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input task-checkbox" type="checkbox" value="" id="task2">
                                        <label class="form-check-label" for="task2">
                                            <span class="task-title">Informe de Laboratorio - Base de Datos</span>
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">Media</span>
                                        <small class="text-muted me-3"><i class="fas fa-clock me-1"></i> 5 días</small>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i> Editar</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-check me-2"></i> Marcar como completada</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i> Eliminar</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="task-details mt-2">
                                    <p class="mb-1 text-muted">Elaborar informe con los resultados de las consultas SQL realizadas en el laboratorio.</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info text-dark me-2">Fundamentos de Bases de Datos</span>
                                        <div class="progress flex-grow-1" style="height: 5px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ms-2 text-muted small">30%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tarea 3 - Baja prioridad -->
                            <div class="list-group-item list-group-item-action task-item" data-priority="low" data-status="pending" data-course="5" data-due="2025-06-10">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input task-checkbox" type="checkbox" value="" id="task3">
                                        <label class="form-check-label" for="task3">
                                            <span class="task-title">Cuestionario Final - Inteligencia Artificial</span>
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-2">Baja</span>
                                        <small class="text-muted me-3"><i class="fas fa-clock me-1"></i> 2 semanas</small>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i> Editar</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-check me-2"></i> Marcar como completada</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i> Eliminar</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="task-details mt-2">
                                    <p class="mb-1 text-muted">Responder cuestionario final sobre redes neuronales y aprendizaje profundo.</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info text-dark me-2">Introducción a la IA</span>
                                        <div class="progress flex-grow-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 15%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ms-2 text-muted small">15%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tarea completada -->
                            <div class="list-group-item list-group-item-action task-item completed-task" data-priority="medium" data-status="completed" data-course="3" data-due="2025-05-26">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input task-checkbox" type="checkbox" value="" id="task4" checked>
                                        <label class="form-check-label text-muted" for="task4">
                                            <span class="task-title"><del>Práctica de ordenamiento - Algoritmos</del></span>
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary me-2">Completada</span>
                                        <small class="text-muted me-3"><i class="fas fa-check-circle me-1"></i> 27 Mayo</small>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="fas fa-undo me-2"></i> Marcar como pendiente</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i> Eliminar</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vista de tablero (oculta por defecto) -->
                    <div id="board-view" class="d-none">
                        <div class="row g-4 p-3">
                            <!-- Columna: Alta prioridad -->
                            <div class="col-md-4">
                                <div class="board-column">
                                    <h6 class="board-column-title"><span class="dot bg-danger"></span> Alta prioridad</h6>
                                    <div class="board-item" data-priority="high" data-course="1" data-due="2025-05-29">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">Proyecto Final - Desarrollo Web</h6>
                                                <p class="card-text small">Completar el proyecto final para el curso de Desarrollo Web Avanzado.</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-info text-dark">Desarrollo Web</span>
                                                    <small class="text-danger">Mañana</small>
                                                </div>
                                                <div class="progress mt-2" style="height: 5px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Columna: Media prioridad -->
                            <div class="col-md-4">
                                <div class="board-column">
                                    <h6 class="board-column-title"><span class="dot bg-warning"></span> Media prioridad</h6>
                                    <div class="board-item" data-priority="medium" data-course="2" data-due="2025-06-02">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">Informe de Laboratorio - Base de Datos</h6>
                                                <p class="card-text small">Elaborar informe con los resultados de las consultas SQL.</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-info text-dark">Bases de Datos</span>
                                                    <small class="text-muted">5 días</small>
                                                </div>
                                                <div class="progress mt-2" style="height: 5px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Columna: Baja prioridad -->
                            <div class="col-md-4">
                                <div class="board-column">
                                    <h6 class="board-column-title"><span class="dot bg-success"></span> Baja prioridad</h6>
                                    <div class="board-item" data-priority="low" data-course="5" data-due="2025-06-10">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">Cuestionario Final - Inteligencia Artificial</h6>
                                                <p class="card-text small">Responder cuestionario final sobre redes neuronales.</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-info text-dark">IA</span>
                                                    <small class="text-muted">2 semanas</small>
                                                </div>
                                                <div class="progress mt-2" style="height: 5px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 15%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir tarea -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Añadir nueva tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTaskForm" action="{{ route('student.tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="task-title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="task-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="task-description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="task-description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="task-due-date" class="form-label">Fecha límite</label>
                            <input type="date" class="form-control" id="task-due-date" name="due_date">
                        </div>
                        <div class="col-md-6">
                            <label for="task-priority" class="form-label">Prioridad</label>
                            <select class="form-select" id="task-priority" name="priority" required>
                                <option value="high">Alta</option>
                                <option value="medium" selected>Media</option>
                                <option value="low">Baja</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="task-course" class="form-label">Curso relacionado</label>
                        <select class="form-select" id="task-course" name="course_id">
                            <option value="">Ninguno</option>
                            <option value="1">Desarrollo Web Avanzado</option>
                            <option value="2">Fundamentos de Bases de Datos</option>
                            <option value="3">Algoritmos Avanzados</option>
                            <option value="4">Sistemas Operativos</option>
                            <option value="5">Introducción a la IA</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="task-reminder" name="reminder">
                        <label class="form-check-label" for="task-reminder">
                            Recibir recordatorio
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveTaskBtn">Guardar tarea</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos para tareas */
    .task-item {
        border-left: 4px solid transparent;
        transition: all 0.2s;
    }
    
    .task-item[data-priority="high"] {
        border-left-color: #dc3545;
    }
    
    .task-item[data-priority="medium"] {
        border-left-color: #ffc107;
    }
    
    .task-item[data-priority="low"] {
        border-left-color: #198754;
    }
    
    .completed-task {
        background-color: rgba(108, 117, 125, 0.05);
        border-left-color: #6c757d;
    }
    
    .task-details {
        display: none;
    }
    
    .task-item:hover .task-details {
        display: block;
    }
    
    /* Estilos para la vista de tablero */
    .board-column {
        height: 100%;
    }
    
    .board-column-title {
        margin-bottom: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    
    .board-item {
        margin-bottom: 15px;
    }
    
    .board-item .card {
        transition: all 0.2s;
    }
    
    .board-item .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtrar tareas por estado (todas, pendientes, completadas)
        const allTasksBtn = document.getElementById('all-tasks-btn');
        const pendingTasksBtn = document.getElementById('pending-tasks-btn');
        const completedTasksBtn = document.getElementById('completed-tasks-btn');
        const taskItems = document.querySelectorAll('.task-item');
        
        allTasksBtn.addEventListener('click', function() {
            allTasksBtn.classList.add('active');
            pendingTasksBtn.classList.remove('active');
            completedTasksBtn.classList.remove('active');
            
            taskItems.forEach(item => {
                item.style.display = 'block';
            });
        });
        
        pendingTasksBtn.addEventListener('click', function() {
            allTasksBtn.classList.remove('active');
            pendingTasksBtn.classList.add('active');
            completedTasksBtn.classList.remove('active');
            
            taskItems.forEach(item => {
                if (item.dataset.status === 'pending') {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        completedTasksBtn.addEventListener('click', function() {
            allTasksBtn.classList.remove('active');
            pendingTasksBtn.classList.remove('active');
            completedTasksBtn.classList.add('active');
            
            taskItems.forEach(item => {
                if (item.dataset.status === 'completed') {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Alternar vistas (lista/tablero)
        const viewModeButtons = document.querySelectorAll('.view-mode-btn');
        const listView = document.getElementById('list-view');
        const boardView = document.getElementById('board-view');
        
        viewModeButtons.forEach(button => {
            button.addEventListener('click', function() {
                viewModeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const viewMode = this.dataset.view;
                
                if (viewMode === 'list') {
                    listView.classList.remove('d-none');
                    boardView.classList.add('d-none');
                } else if (viewMode === 'board') {
                    listView.classList.add('d-none');
                    boardView.classList.remove('d-none');
                }
            });
        });
        
        // Búsqueda y filtrado
        const taskSearch = document.getElementById('task-search');
        const courseFilter = document.getElementById('course-filter');
        const priorityFilter = document.getElementById('priority-filter');
        const dueDateFilter = document.getElementById('due-date-filter');
        
        function filterTasks() {
            const searchTerm = taskSearch.value.toLowerCase();
            const selectedCourse = courseFilter.value;
            const selectedPriority = priorityFilter.value;
            const selectedDueDate = dueDateFilter.value;
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            const weekEnd = new Date(today);
            weekEnd.setDate(weekEnd.getDate() + 7);
            
            const monthEnd = new Date(today);
            monthEnd.setMonth(monthEnd.getMonth() + 1);
            
            taskItems.forEach(item => {
                const taskTitle = item.querySelector('.task-title').textContent.toLowerCase();
                const taskDescription = item.querySelector('.task-details p') ? 
                                       item.querySelector('.task-details p').textContent.toLowerCase() : '';
                const taskCourse = item.dataset.course;
                const taskPriority = item.dataset.priority;
                const taskDueDate = new Date(item.dataset.due);
                
                let showTask = true;
                
                // Búsqueda por texto
                if (searchTerm && !taskTitle.includes(searchTerm) && !taskDescription.includes(searchTerm)) {
                    showTask = false;
                }
                
                // Filtro por curso
                if (selectedCourse !== 'all' && taskCourse !== selectedCourse) {
                    showTask = false;
                }
                
                // Filtro por prioridad
                if (selectedPriority !== 'all' && taskPriority !== selectedPriority) {
                    showTask = false;
                }
                
                // Filtro por fecha
                if (selectedDueDate !== 'all') {
                    if (selectedDueDate === 'today' && !(taskDueDate.toDateString() === today.toDateString())) {
                        showTask = false;
                    } else if (selectedDueDate === 'tomorrow' && !(taskDueDate.toDateString() === tomorrow.toDateString())) {
                        showTask = false;
                    } else if (selectedDueDate === 'week' && !(taskDueDate >= today && taskDueDate <= weekEnd)) {
                        showTask = false;
                    } else if (selectedDueDate === 'month' && !(taskDueDate >= today && taskDueDate <= monthEnd)) {
                        showTask = false;
                    } else if (selectedDueDate === 'overdue' && !(taskDueDate < today)) {
                        showTask = false;
                    }
                }
                
                // Mostrar u ocultar tarea
                item.style.display = showTask ? 'block' : 'none';
            });
        }
        
        taskSearch.addEventListener('input', filterTasks);
        courseFilter.addEventListener('change', filterTasks);
        priorityFilter.addEventListener('change', filterTasks);
        dueDateFilter.addEventListener('change', filterTasks);
        
        // Gestionar checkboxes
        const taskCheckboxes = document.querySelectorAll('.task-checkbox');
        
        taskCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const taskItem = this.closest('.task-item');
                const taskTitle = taskItem.querySelector('.task-title');
                
                if (this.checked) {
                    taskItem.classList.add('completed-task');
                    taskItem.dataset.status = 'completed';
                    taskTitle.innerHTML = `<del>${taskTitle.textContent}</del>`;
                } else {
                    taskItem.classList.remove('completed-task');
                    taskItem.dataset.status = 'pending';
                    taskTitle.innerHTML = taskTitle.textContent;
                }
            });
        });
        
        // Guardar nueva tarea
        const saveTaskBtn = document.getElementById('saveTaskBtn');
        const addTaskForm = document.getElementById('addTaskForm');
        
        saveTaskBtn.addEventListener('click', function() {
            if (addTaskForm.checkValidity()) {
                // Aquí iría la lógica para guardar la tarea
                // En un escenario real, esto sería un submit del formulario
                // Por ahora, simulamos una respuesta exitosa
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
                modal.hide();
                
                // Mostrar alerta de éxito
                const alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Tarea añadida correctamente
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
                
                // Limpiar formulario
                addTaskForm.reset();
            } else {
                addTaskForm.reportValidity();
            }
        });
    });
</script>
@endpush
