@extends('layouts.mentor')

@section('title', 'Calendario de Sesiones - MentorHub')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Calendario de Sesiones</h1>
        <div>
            <a href="#" class="btn btn-sm btn-success shadow-sm me-2" id="exportCalendarBtn">
                <i class="fas fa-file-export fa-sm text-white-50"></i> Exportar Calendario
            </a>
            <a href="{{ route('mentor.sessions.create') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus-circle fa-sm text-white-50"></i> Nueva Sesión
            </a>
        </div>
    </div>
    
    @include('partials.alerts')

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado de sesiones</label>
                        <div class="form-check">
                            <input class="form-check-input filter-status" type="checkbox" value="scheduled" id="filter-scheduled" checked>
                            <label class="form-check-label" for="filter-scheduled">
                                <span class="badge bg-primary me-1">•</span> Programadas
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-status" type="checkbox" value="pending" id="filter-pending" checked>
                            <label class="form-check-label" for="filter-pending">
                                <span class="badge bg-warning me-1">•</span> Pendientes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-status" type="checkbox" value="completed" id="filter-completed" checked>
                            <label class="form-check-label" for="filter-completed">
                                <span class="badge bg-success me-1">•</span> Completadas
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-status" type="checkbox" value="cancelled" id="filter-cancelled">
                            <label class="form-check-label" for="filter-cancelled">
                                <span class="badge bg-danger me-1">•</span> Canceladas
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estudiantes</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control form-control-sm" placeholder="Buscar estudiante..." id="studentSearch">
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="clearStudentSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div id="studentList" class="overflow-auto" style="max-height: 200px;">
                            <!-- Lista de estudiantes se cargará dinámicamente -->
                            <div class="form-check">
                                <input class="form-check-input filter-student" type="checkbox" value="all" id="filter-all-students" checked>
                                <label class="form-check-label" for="filter-all-students">
                                    Todos los estudiantes
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cursos</label>
                        <div id="courseList" class="overflow-auto" style="max-height: 200px;">
                            <!-- Lista de cursos se cargará dinámicamente -->
                            <div class="form-check">
                                <input class="form-check-input filter-course" type="checkbox" value="all" id="filter-all-courses" checked>
                                <label class="form-check-label" for="filter-all-courses">
                                    Todos los cursos
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" id="applyFilters">
                            <i class="fas fa-filter me-1"></i> Aplicar Filtros
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="resetFilters">
                            <i class="fas fa-undo me-1"></i> Restablecer
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Próximas Sesiones</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="upcomingSessionsList">
                        <!-- Las sesiones próximas se cargarán dinámicamente -->
                        @foreach(range(1, 3) as $index)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Sesión con Estudiante {{ $index }}</h6>
                                    <small class="text-primary">{{ now()->addDays($index)->format('d/m H:i') }}</small>
                                </div>
                                <p class="mb-1 small">Tema: Introducción a Laravel</p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">Duración: 1 hora</small>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de sesión -->
<div class="modal fade" id="sessionDetailsModal" tabindex="-1" aria-labelledby="sessionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionDetailsModalLabel">Detalles de la Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 id="session-title">Título de la sesión</h4>
                        <div class="mb-3">
                            <span class="badge bg-primary" id="session-status">Programada</span>
                            <span class="text-muted ms-2" id="session-datetime">Fecha y hora</span>
                        </div>
                        <div class="mb-3">
                            <h6>Descripción:</h6>
                            <p id="session-description">Descripción de la sesión</p>
                        </div>
                        <div class="mb-3">
                            <h6>Enlace de reunión:</h6>
                            <a href="#" id="session-meeting-url" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-video me-1"></i> Unirse a la reunión
                            </a>
                        </div>
                        <div class="mb-3" id="session-notes-container">
                            <h6>Notas:</h6>
                            <p id="session-notes">Notas de la sesión</p>
                        </div>
                    </div>
                    <div class="col-md-4 border-start">
                        <div class="mb-3">
                            <h6>Estudiante:</h6>
                            <div class="d-flex align-items-center">
                                <img src="/img/undraw_profile.svg" class="rounded-circle me-2" width="32" height="32">
                                <span id="session-student">Nombre del estudiante</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>Curso:</h6>
                            <p id="session-course">Nombre del curso</p>
                        </div>
                        <div class="mb-3">
                            <h6>Duración:</h6>
                            <p id="session-duration">60 minutos</p>
                        </div>
                    </div>
                </div>
                
                <div id="session-review-container" class="mt-4 border-top pt-3 d-none">
                    <h5>Evaluación de la sesión</h5>
                    <div id="session-rating" class="mb-2">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-secondary"></i>
                        <span class="ms-2">4.0/5.0</span>
                    </div>
                    <p id="session-review-comment">Comentario de la evaluación</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning d-none" id="cancelSessionBtn">
                    <i class="fas fa-ban me-1"></i> Cancelar Sesión
                </button>
                <button type="button" class="btn btn-success d-none" id="completeSessionBtn">
                    <i class="fas fa-check-circle me-1"></i> Marcar como Completada
                </button>
                <button type="button" class="btn btn-primary d-none" id="addReviewBtn">
                    <i class="fas fa-star me-1"></i> Añadir Evaluación
                </button>
                <a href="#" class="btn btn-info d-none" id="editSessionBtn">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para añadir evaluación -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReviewModalLabel">Añadir Evaluación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addReviewForm">
                    <input type="hidden" id="review-session-id">
                    <div class="mb-3">
                        <label class="form-label">Calificación:</label>
                        <div class="rating">
                            <i class="far fa-star fa-2x" data-rating="1"></i>
                            <i class="far fa-star fa-2x" data-rating="2"></i>
                            <i class="far fa-star fa-2x" data-rating="3"></i>
                            <i class="far fa-star fa-2x" data-rating="4"></i>
                            <i class="far fa-star fa-2x" data-rating="5"></i>
                            <input type="hidden" id="review-rating" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="review-comment" class="form-label">Comentario:</label>
                        <textarea class="form-control" id="review-comment" rows="4" placeholder="Comparte tu experiencia sobre esta sesión..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveReviewBtn">Guardar Evaluación</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">

<style>
    /* Estilos personalizados para el calendario */
    .fc-event {
        cursor: pointer;
        border-radius: 4px;
    }
    
    .fc-event.scheduled {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .fc-event.pending {
        background-color: #f6c23e;
        border-color: #f6c23e;
    }
    
    .fc-event.completed {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    
    .fc-event.cancelled {
        background-color: #e74a3b;
        border-color: #e74a3b;
        text-decoration: line-through;
    }
    
    /* Estilos para la calificación con estrellas */
    .rating {
        display: flex;
        gap: 5px;
    }
    
    .rating i {
        cursor: pointer;
        color: #dddddd;
    }
    
    .rating i.fas {
        color: #f6c23e;
    }
    
    /* Estilos para las próximas sesiones */
    #upcomingSessionsList .list-group-item {
        transition: all 0.2s;
    }
    
    #upcomingSessionsList .list-group-item:hover {
        background-color: #f8f9fc;
    }
    

</style>
@endpush

@push('scripts')
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales-all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el calendario
        const calendarEl = document.getElementById('calendar');
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            allDaySlot: false,
            slotMinTime: '08:00:00',
            slotMaxTime: '20:00:00',
            slotDuration: '00:30:00',
            navLinks: true,
            selectable: true,
            editable: true,
            dayMaxEvents: true,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            // Cargar eventos desde el servidor
            events: function(info, successCallback, failureCallback) {
                // Simulación de datos para demostración
                const events = [
                    {
                        id: '1',
                        title: 'Sesión con María García',
                        start: '2025-05-30T10:00:00',
                        end: '2025-05-30T11:00:00',
                        classNames: ['scheduled'],
                        extendedProps: {
                            status: 'scheduled',
                            student: 'María García',
                            course: 'Desarrollo Web Frontend',
                            description: 'Revisión de proyecto final',
                            meetingUrl: 'https://meet.google.com/abc-defg-hij',
                            duration: 60
                        }
                    },
                    {
                        id: '2',
                        title: 'Sesión con Carlos Rodríguez',
                        start: '2025-05-31T15:00:00',
                        end: '2025-05-31T16:30:00',
                        classNames: ['pending'],
                        extendedProps: {
                            status: 'pending',
                            student: 'Carlos Rodríguez',
                            course: 'JavaScript Avanzado',
                            description: 'Dudas sobre APIs y promesas',
                            duration: 90
                        }
                    },
                    {
                        id: '3',
                        title: 'Sesión con Ana Martínez',
                        start: '2025-05-29T12:00:00',
                        end: '2025-05-29T13:00:00',
                        classNames: ['completed'],
                        extendedProps: {
                            status: 'completed',
                            student: 'Ana Martínez',
                            course: 'Base de Datos SQL',
                            description: 'Optimización de consultas',
                            meetingUrl: 'https://meet.google.com/xyz-abcd-efg',
                            duration: 60,
                            hasReview: true,
                            rating: 5,
                            reviewComment: 'Excelente sesión, muy clara la explicación.'
                        }
                    },
                    {
                        id: '4',
                        title: 'Sesión con Pedro López',
                        start: '2025-05-28T17:00:00',
                        end: '2025-05-28T18:00:00',
                        classNames: ['cancelled'],
                        extendedProps: {
                            status: 'cancelled',
                            student: 'Pedro López',
                            course: 'Python para Data Science',
                            description: 'Introducción a pandas',
                            duration: 60,
                            cancellationReason: 'El estudiante solicitó reprogramar la sesión'
                        }
                    }
                ];
                
                successCallback(events);
            },
            // Manejar clic en un evento
            eventClick: function(info) {
                showSessionDetails(info.event);
            },
            // Manejar selección de fecha/hora para crear nuevo evento
            select: function(info) {
                window.location.href = `{{ route('mentor.sessions.create') }}?date=${info.startStr}`;
            }
        });
        
        calendar.render();
        
        // Función para mostrar detalles de la sesión
        function showSessionDetails(event) {
            const modal = document.getElementById('sessionDetailsModal');
            const bsModal = new bootstrap.Modal(modal);
            
            // Llenar datos del modal
            document.getElementById('session-title').textContent = event.title;
            document.getElementById('session-status').textContent = getStatusText(event.extendedProps.status);
            document.getElementById('session-status').className = `badge bg-${getStatusColor(event.extendedProps.status)}`;
            document.getElementById('session-datetime').textContent = formatDateTime(event.start, event.end);
            document.getElementById('session-description').textContent = event.extendedProps.description || 'Sin descripción';
            document.getElementById('session-student').textContent = event.extendedProps.student;
            document.getElementById('session-course').textContent = event.extendedProps.course;
            document.getElementById('session-duration').textContent = `${event.extendedProps.duration} minutos`;
            
            // Configurar enlace de reunión
            const meetingUrlBtn = document.getElementById('session-meeting-url');
            if (event.extendedProps.meetingUrl) {
                meetingUrlBtn.href = event.extendedProps.meetingUrl;
                meetingUrlBtn.classList.remove('d-none');
            } else {
                meetingUrlBtn.classList.add('d-none');
            }
            
            // Mostrar/ocultar notas según corresponda
            const notesContainer = document.getElementById('session-notes-container');
            if (event.extendedProps.mentorNotes) {
                document.getElementById('session-notes').textContent = event.extendedProps.mentorNotes;
                notesContainer.classList.remove('d-none');
            } else {
                notesContainer.classList.add('d-none');
            }
            
            // Mostrar/ocultar evaluación según corresponda
            const reviewContainer = document.getElementById('session-review-container');
            if (event.extendedProps.hasReview) {
                // Mostrar estrellas según calificación
                const rating = event.extendedProps.rating;
                const ratingHtml = generateRatingStars(rating);
                document.getElementById('session-rating').innerHTML = ratingHtml + `<span class="ms-2">${rating}/5.0</span>`;
                document.getElementById('session-review-comment').textContent = event.extendedProps.reviewComment || 'Sin comentarios';
                reviewContainer.classList.remove('d-none');
            } else {
                reviewContainer.classList.add('d-none');
            }
            
            // Configurar botones según el estado de la sesión
            const cancelBtn = document.getElementById('cancelSessionBtn');
            const completeBtn = document.getElementById('completeSessionBtn');
            const reviewBtn = document.getElementById('addReviewBtn');
            const editBtn = document.getElementById('editSessionBtn');
            
            // Ocultar todos los botones primero
            cancelBtn.classList.add('d-none');
            completeBtn.classList.add('d-none');
            reviewBtn.classList.add('d-none');
            editBtn.classList.add('d-none');
            
            // Mostrar botones según el estado
            switch (event.extendedProps.status) {
                case 'scheduled':
                    cancelBtn.classList.remove('d-none');
                    editBtn.classList.remove('d-none');
                    editBtn.href = `{{ url('mentor/sessions') }}/${event.id}/edit`;
                    
                    // Si la sesión está programada para hoy o ya pasó, mostrar botón de completar
                    const today = new Date();
                    const eventDate = new Date(event.start);
                    if (eventDate.toDateString() === today.toDateString() || eventDate < today) {
                        completeBtn.classList.remove('d-none');
                    }
                    break;
                    
                case 'pending':
                    cancelBtn.classList.remove('d-none');
                    editBtn.classList.remove('d-none');
                    editBtn.href = `{{ url('mentor/sessions') }}/${event.id}/edit`;
                    break;
                    
                case 'completed':
                    if (!event.extendedProps.hasReview) {
                        reviewBtn.classList.remove('d-none');
                    }
                    break;
            }
            
            // Configurar IDs para los botones de acción
            cancelBtn.dataset.sessionId = event.id;
            completeBtn.dataset.sessionId = event.id;
            reviewBtn.dataset.sessionId = event.id;
            
            bsModal.show();
        }
        
        // Funciones auxiliares
        function getStatusText(status) {
            const statusMap = {
                'scheduled': 'Programada',
                'pending': 'Pendiente',
                'completed': 'Completada',
                'cancelled': 'Cancelada'
            };
            return statusMap[status] || status;
        }
        
        function getStatusColor(status) {
            const colorMap = {
                'scheduled': 'primary',
                'pending': 'warning',
                'completed': 'success',
                'cancelled': 'danger'
            };
            return colorMap[status] || 'secondary';
        }
        
        function formatDateTime(start, end) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            let result = start.toLocaleDateString('es-ES', options);
            
            if (end) {
                const endOptions = { hour: '2-digit', minute: '2-digit' };
                result += ` - ${end.toLocaleTimeString('es-ES', endOptions)}`;
            }
            
            return result;
        }
        
        function generateRatingStars(rating) {
            let html = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    html += '<i class="fas fa-star text-warning"></i>';
                } else {
                    html += '<i class="fas fa-star text-secondary"></i>';
                }
            }
            return html;
        }
        
        // Manejar clic en botones de acción
        document.getElementById('cancelSessionBtn').addEventListener('click', function() {
            const sessionId = this.dataset.sessionId;
            if (confirm('¿Estás seguro de que deseas cancelar esta sesión?')) {
                // Aquí iría la llamada AJAX para cancelar la sesión
                console.log(`Cancelando sesión ${sessionId}`);
            }
        });
        
        document.getElementById('completeSessionBtn').addEventListener('click', function() {
            const sessionId = this.dataset.sessionId;
            if (confirm('¿Confirmas que esta sesión ha sido completada?')) {
                // Aquí iría la llamada AJAX para marcar como completada
                console.log(`Marcando como completada la sesión ${sessionId}`);
            }
        });
        
        document.getElementById('addReviewBtn').addEventListener('click', function() {
            const sessionId = this.dataset.sessionId;
            document.getElementById('review-session-id').value = sessionId;
            
            // Ocultar modal de detalles y mostrar modal de evaluación
            bootstrap.Modal.getInstance(document.getElementById('sessionDetailsModal')).hide();
            const reviewModal = new bootstrap.Modal(document.getElementById('addReviewModal'));
            reviewModal.show();
        });
        
        // Manejar calificación con estrellas
        const ratingStars = document.querySelectorAll('.rating i');
        ratingStars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const rating = parseInt(this.dataset.rating);
                highlightStars(rating);
            });
            
            star.addEventListener('mouseout', function() {
                const currentRating = parseInt(document.getElementById('review-rating').value);
                highlightStars(currentRating);
            });
            
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                document.getElementById('review-rating').value = rating;
                highlightStars(rating);
            });
        });
        
        function highlightStars(rating) {
            ratingStars.forEach(star => {
                const starRating = parseInt(star.dataset.rating);
                if (starRating <= rating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                }
            });
        }
        
        // Guardar evaluación
        document.getElementById('saveReviewBtn').addEventListener('click', function() {
            const sessionId = document.getElementById('review-session-id').value;
            const rating = document.getElementById('review-rating').value;
            const comment = document.getElementById('review-comment').value;
            
            if (rating === '0') {
                alert('Por favor, selecciona una calificación');
                return;
            }
            
            // Aquí iría la llamada AJAX para guardar la evaluación
            console.log(`Guardando evaluación para sesión ${sessionId}: ${rating} estrellas, comentario: ${comment}`);
            
            // Cerrar modal y mostrar mensaje de éxito
            bootstrap.Modal.getInstance(document.getElementById('addReviewModal')).hide();
            alert('¡Evaluación guardada con éxito!');
        });
        
        // Exportar calendario
        document.getElementById('exportCalendarBtn').addEventListener('click', function() {
            // Aquí iría la lógica para exportar el calendario
            alert('Esta funcionalidad exportará tus sesiones a un formato compatible con Google Calendar, Outlook, etc.');
        });
        
        // Filtros del calendario
        document.getElementById('applyFilters').addEventListener('click', function() {
            // Aquí iría la lógica para aplicar filtros
            alert('Filtros aplicados');
        });
        
        document.getElementById('resetFilters').addEventListener('click', function() {
            // Restablecer todos los checkboxes a su estado inicial
            document.querySelectorAll('.filter-status').forEach(checkbox => {
                checkbox.checked = checkbox.id !== 'filter-cancelled';
            });
            
            document.getElementById('filter-all-students').checked = true;
            document.getElementById('filter-all-courses').checked = true;
            
            // Limpiar campo de búsqueda
            document.getElementById('studentSearch').value = '';
            
            // Aquí iría la lógica para restablecer filtros
            alert('Filtros restablecidos');
        });
        
        // Cargar lista de próximas sesiones
        loadUpcomingSessions();
        
        function loadUpcomingSessions() {
            // Aquí iría la llamada AJAX para cargar las próximas sesiones
            // Por ahora usamos datos de ejemplo
            const upcomingSessions = [
                {
                    id: '1',
                    title: 'Sesión con María García',
                    datetime: '2025-05-30T10:00:00',
                    duration: 60,
                    course: 'Desarrollo Web Frontend'
                },
                {
                    id: '2',
                    title: 'Sesión con Carlos Rodríguez',
                    datetime: '2025-05-31T15:00:00',
                    duration: 90,
                    course: 'JavaScript Avanzado'
                },
                {
                    id: '5',
                    title: 'Sesión con Laura Sánchez',
                    datetime: '2025-06-01T11:00:00',
                    duration: 60,
                    course: 'React Fundamentals'
                }
            ];
            
            const container = document.getElementById('upcomingSessionsList');
            container.innerHTML = '';
            
            if (upcomingSessions.length === 0) {
                container.innerHTML = '<div class="list-group-item">No hay sesiones próximas</div>';
                return;
            }
            
            upcomingSessions.forEach(session => {
                const date = new Date(session.datetime);
                const options = { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                const formattedDate = date.toLocaleDateString('es-ES', options);
                
                const item = document.createElement('div');
                item.className = 'list-group-item';
                item.innerHTML = `
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${session.title}</h6>
                        <small class="text-primary">${formattedDate}</small>
                    </div>
                    <p class="mb-1 small">${session.course}</p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">Duración: ${session.duration} min</small>
                        <a href="#" class="btn btn-sm btn-outline-primary view-session" data-session-id="${session.id}">Ver detalles</a>
                    </div>
                `;
                
                container.appendChild(item);
            });
            
            // Añadir event listeners a los botones de ver detalles
            document.querySelectorAll('.view-session').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sessionId = this.dataset.sessionId;
                    // Buscar el evento en el calendario y mostrar sus detalles
                    const event = calendar.getEventById(sessionId);
                    if (event) {
                        showSessionDetails(event);
                    }
                });
            });
        }
        

    });
</script>
@endpush
