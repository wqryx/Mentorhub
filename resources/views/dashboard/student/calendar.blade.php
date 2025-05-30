@extends('layouts.dashboard.student')

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Calendario</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="month-view-btn">
                    <i class="fas fa-calendar-alt me-1"></i> Mes
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary active" id="week-view-btn">
                    <i class="fas fa-calendar-week me-1"></i> Semana
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="day-view-btn">
                    <i class="fas fa-calendar-day me-1"></i> Día
                </button>
            </div>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                <i class="fas fa-plus me-1"></i> Añadir evento
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="prev-btn">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="next-btn">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="today-btn">
                                Hoy
                            </button>
                        </div>
                        <h4 id="calendar-title" class="mb-0">Mayo 2025</h4>
                        <div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="calendarFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Filtrar
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="calendarFilterDropdown">
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="class" id="filter-class" checked>
                                                <label class="form-check-label" for="filter-class">
                                                    <span class="badge bg-primary me-1">•</span> Clases
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="deadline" id="filter-deadline" checked>
                                                <label class="form-check-label" for="filter-deadline">
                                                    <span class="badge bg-danger me-1">•</span> Entregas
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="exam" id="filter-exam" checked>
                                                <label class="form-check-label" for="filter-exam">
                                                    <span class="badge bg-warning me-1">•</span> Exámenes
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="personal" id="filter-personal" checked>
                                                <label class="form-check-label" for="filter-personal">
                                                    <span class="badge bg-info me-1">•</span> Personales
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="all-courses" id="filter-all-courses" checked>
                                                <label class="form-check-label" for="filter-all-courses">
                                                    Todos los cursos
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="calendar-container">
                        <!-- Vista del calendario (semana por defecto) -->
                        <div id="calendar-week-view" class="calendar-view">
                            <div class="calendar-header">
                                <div class="calendar-header-cell calendar-time-col"></div>
                                <div class="calendar-header-cell">Lunes</div>
                                <div class="calendar-header-cell">Martes</div>
                                <div class="calendar-header-cell">Miércoles</div>
                                <div class="calendar-header-cell">Jueves</div>
                                <div class="calendar-header-cell">Viernes</div>
                                <div class="calendar-header-cell">Sábado</div>
                                <div class="calendar-header-cell">Domingo</div>
                            </div>
                            <div class="calendar-body">
                                <!-- Horas -->
                                <div class="calendar-time-col">
                                    @for ($hour = 8; $hour <= 20; $hour++)
                                        <div class="calendar-time-slot">{{ $hour }}:00</div>
                                    @endfor
                                </div>
                                
                                <!-- Días de la semana (filas para cada hora) -->
                                @for ($day = 1; $day <= 7; $day++)
                                    <div class="calendar-day-col" data-day="{{ $day }}">
                                        @for ($hour = 8; $hour <= 20; $hour++)
                                            <div class="calendar-slot" data-hour="{{ $hour }}"></div>
                                        @endfor
                                        
                                        <!-- Eventos de ejemplo (se cargarían dinámicamente) -->
                                        @if($day == 1)
                                            <div class="calendar-event bg-primary" style="top: 120px; height: 90px;">
                                                <div class="calendar-event-title">Clase de Programación</div>
                                                <div class="calendar-event-time">10:00 - 11:30</div>
                                            </div>
                                        @elseif($day == 3)
                                            <div class="calendar-event bg-warning" style="top: 240px; height: 60px;">
                                                <div class="calendar-event-title">Examen de Base de Datos</div>
                                                <div class="calendar-event-time">12:00 - 13:00</div>
                                            </div>
                                        @elseif($day == 4)
                                            <div class="calendar-event bg-danger" style="top: 360px; height: 30px;">
                                                <div class="calendar-event-title">Entrega de Proyecto</div>
                                                <div class="calendar-event-time">14:00</div>
                                            </div>
                                        @elseif($day == 6)
                                            <div class="calendar-event bg-info" style="top: 480px; height: 120px;">
                                                <div class="calendar-event-title">Estudio en grupo</div>
                                                <div class="calendar-event-time">16:00 - 18:00</div>
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                        
                        <!-- Vista mensual (oculta por defecto) -->
                        <div id="calendar-month-view" class="calendar-view d-none">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Lun</th>
                                        <th>Mar</th>
                                        <th>Mié</th>
                                        <th>Jue</th>
                                        <th>Vie</th>
                                        <th>Sáb</th>
                                        <th>Dom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($week = 1; $week <= 5; $week++)
                                        <tr>
                                            @for ($day = 1; $day <= 7; $day++)
                                                <td class="calendar-month-day">
                                                    <div class="calendar-date">{{ (($week-1)*7 + $day) <= 31 ? (($week-1)*7 + $day) : '' }}</div>
                                                    @if($week == 1 && $day == 1)
                                                        <div class="calendar-month-event bg-primary">Clase de Programación</div>
                                                    @elseif($week == 2 && $day == 3)
                                                        <div class="calendar-month-event bg-warning">Examen de Base de Datos</div>
                                                    @elseif($week == 2 && $day == 4)
                                                        <div class="calendar-month-event bg-danger">Entrega de Proyecto</div>
                                                    @elseif($week == 3 && $day == 6)
                                                        <div class="calendar-month-event bg-info">Estudio en grupo</div>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Vista diaria (oculta por defecto) -->
                        <div id="calendar-day-view" class="calendar-view d-none">
                            <div class="calendar-header">
                                <div class="calendar-header-cell calendar-time-col"></div>
                                <div class="calendar-header-cell">Miércoles, 28 de Mayo de 2025</div>
                            </div>
                            <div class="calendar-body">
                                <!-- Horas -->
                                <div class="calendar-time-col">
                                    @for ($hour = 8; $hour <= 20; $hour++)
                                        <div class="calendar-time-slot">{{ $hour }}:00</div>
                                    @endfor
                                </div>
                                
                                <!-- Columna del día -->
                                <div class="calendar-day-col calendar-day-full" data-day="1">
                                    @for ($hour = 8; $hour <= 20; $hour++)
                                        <div class="calendar-slot" data-hour="{{ $hour }}"></div>
                                    @endfor
                                    
                                    <!-- Evento de ejemplo para la vista diaria -->
                                    <div class="calendar-event bg-warning" style="top: 240px; height: 60px;">
                                        <div class="calendar-event-title">Examen de Base de Datos</div>
                                        <div class="calendar-event-time">12:00 - 13:00</div>
                                        <div class="calendar-event-location">Aula Virtual 3</div>
                                        <div class="calendar-event-description">Preparar temas de normalización y SQL avanzado</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos eventos -->
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Eventos de hoy</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Examen de Base de Datos</h6>
                                <small class="text-warning">12:00</small>
                            </div>
                            <p class="mb-1 small">Aula Virtual 3</p>
                            <small class="text-muted">Curso: Fundamentos de Bases de Datos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Próximos eventos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Entrega de Proyecto</h6>
                                <small class="text-danger">Mañana, 14:00</small>
                            </div>
                            <p class="mb-1 small">Subir en la plataforma</p>
                            <small class="text-muted">Curso: Desarrollo Web Avanzado</small>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Estudio en grupo</h6>
                                <small class="text-info">Sábado, 16:00</small>
                            </div>
                            <p class="mb-1 small">Biblioteca Central</p>
                            <small class="text-muted">Curso: Algoritmos Avanzados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Entregas pendientes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Proyecto Final</h6>
                                <small class="text-danger">Mañana</small>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">Desarrollo Web Avanzado</small>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Informe de Laboratorio</h6>
                                <small class="text-warning">3 días</small>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">Sistemas Operativos</small>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Cuestionario Final</h6>
                                <small class="text-primary">1 semana</small>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 15%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">Introducción a la Inteligencia Artificial</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir evento -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventModalLabel">Añadir nuevo evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEventForm">
                    <div class="mb-3">
                        <label for="event-title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="event-title" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="event-date" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="event-date" required>
                        </div>
                        <div class="col-md-3">
                            <label for="event-start-time" class="form-label">Hora inicio</label>
                            <input type="time" class="form-control" id="event-start-time" required>
                        </div>
                        <div class="col-md-3">
                            <label for="event-end-time" class="form-label">Hora fin</label>
                            <input type="time" class="form-control" id="event-end-time">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="event-location" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="event-location">
                    </div>
                    <div class="mb-3">
                        <label for="event-type" class="form-label">Tipo de evento</label>
                        <select class="form-select" id="event-type" required>
                            <option value="class">Clase</option>
                            <option value="deadline">Entrega</option>
                            <option value="exam">Examen</option>
                            <option value="personal">Personal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event-course" class="form-label">Curso (opcional)</label>
                        <select class="form-select" id="event-course">
                            <option value="">Ninguno</option>
                            <option value="1">Desarrollo Web Avanzado</option>
                            <option value="2">Fundamentos de Bases de Datos</option>
                            <option value="3">Algoritmos Avanzados</option>
                            <option value="4">Sistemas Operativos</option>
                            <option value="5">Introducción a la Inteligencia Artificial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event-description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="event-description" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="event-reminder">
                        <label class="form-check-label" for="event-reminder">
                            Recibir recordatorio
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEventBtn">Guardar evento</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos del calendario */
    .calendar-view {
        width: 100%;
        overflow-x: auto;
    }
    
    .calendar-header {
        display: flex;
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }
    
    .calendar-header-cell {
        flex: 1;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        min-width: 100px;
    }
    
    .calendar-time-col {
        width: 60px;
        min-width: 60px;
        border-right: 1px solid #dee2e6;
    }
    
    .calendar-body {
        display: flex;
        position: relative;
        min-height: 650px;
    }
    
    .calendar-time-slot {
        height: 60px;
        padding: 5px;
        text-align: right;
        color: #6c757d;
        font-size: 0.8rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .calendar-day-col {
        flex: 1;
        position: relative;
        min-width: 100px;
        border-right: 1px solid #dee2e6;
    }
    
    .calendar-day-full {
        flex: 1;
    }
    
    .calendar-slot {
        height: 60px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .calendar-event {
        position: absolute;
        left: 2px;
        right: 2px;
        padding: 5px;
        border-radius: 4px;
        color: white;
        font-size: 0.8rem;
        overflow: hidden;
        cursor: pointer;
        z-index: 1;
        transition: all 0.2s;
    }
    
    .calendar-event:hover {
        z-index: 2;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .calendar-event-title {
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .calendar-event-time, .calendar-event-location, .calendar-event-description {
        font-size: 0.75rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Estilos para la vista mensual */
    .calendar-month-day {
        height: 100px;
        padding: 5px !important;
        vertical-align: top;
    }
    
    .calendar-date {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .calendar-month-event {
        margin-bottom: 2px;
        padding: 2px 5px;
        border-radius: 3px;
        font-size: 0.75rem;
        color: white;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }
    
    /* Media queries para responsive */
    @media (max-width: 768px) {
        .calendar-event-location, .calendar-event-description {
            display: none;
        }
        
        .calendar-month-day {
            height: 80px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cambiar entre vistas de calendario
        const monthViewBtn = document.getElementById('month-view-btn');
        const weekViewBtn = document.getElementById('week-view-btn');
        const dayViewBtn = document.getElementById('day-view-btn');
        const calendarMonthView = document.getElementById('calendar-month-view');
        const calendarWeekView = document.getElementById('calendar-week-view');
        const calendarDayView = document.getElementById('calendar-day-view');
        
        monthViewBtn.addEventListener('click', function() {
            calendarMonthView.classList.remove('d-none');
            calendarWeekView.classList.add('d-none');
            calendarDayView.classList.add('d-none');
            
            monthViewBtn.classList.add('active');
            weekViewBtn.classList.remove('active');
            dayViewBtn.classList.remove('active');
        });
        
        weekViewBtn.addEventListener('click', function() {
            calendarMonthView.classList.add('d-none');
            calendarWeekView.classList.remove('d-none');
            calendarDayView.classList.add('d-none');
            
            monthViewBtn.classList.remove('active');
            weekViewBtn.classList.add('active');
            dayViewBtn.classList.remove('active');
        });
        
        dayViewBtn.addEventListener('click', function() {
            calendarMonthView.classList.add('d-none');
            calendarWeekView.classList.add('d-none');
            calendarDayView.classList.remove('d-none');
            
            monthViewBtn.classList.remove('active');
            weekViewBtn.classList.remove('active');
            dayViewBtn.classList.add('active');
        });
        
        // Guardar evento
        const saveEventBtn = document.getElementById('saveEventBtn');
        const addEventForm = document.getElementById('addEventForm');
        
        saveEventBtn.addEventListener('click', function() {
            if (addEventForm.checkValidity()) {
                // Aquí iría la lógica para guardar el evento
                // Simulamos un guardado exitoso
                const modal = bootstrap.Modal.getInstance(document.getElementById('addEventModal'));
                modal.hide();
                
                // Mostrar alerta de éxito
                const alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Evento añadido correctamente
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
                
                // Limpiar formulario
                addEventForm.reset();
            } else {
                addEventForm.reportValidity();
            }
        });
        
        // Filtros del calendario
        const filterCheckboxes = document.querySelectorAll('.dropdown-menu .form-check-input');
        
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Aquí iría la lógica para filtrar eventos
                console.log(`Filtro ${this.value}: ${this.checked}`);
            });
        });
        
        // Navegación del calendario (prev, next, today)
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const todayBtn = document.getElementById('today-btn');
        const calendarTitle = document.getElementById('calendar-title');
        
        prevBtn.addEventListener('click', function() {
            // Simular cambio a la semana/mes anterior
            calendarTitle.textContent = 'Abril 2025';
        });
        
        nextBtn.addEventListener('click', function() {
            // Simular cambio a la semana/mes siguiente
            calendarTitle.textContent = 'Junio 2025';
        });
        
        todayBtn.addEventListener('click', function() {
            // Simular cambio a la semana/mes actual
            calendarTitle.textContent = 'Mayo 2025';
        });
    });
</script>
@endpush
