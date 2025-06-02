@extends('layouts.dashboard.student')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/student-dashboard.css') }}">
@endpush

@section('dashboard-content')
<div class="container-fluid dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard</h1>
    </div>

    <!-- Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="welcome-card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="welcome-title">¡Bienvenido, {{ auth()->user()->name }}!</h2>
                            <p class="welcome-text">Continúa con tu aprendizaje. Tienes {{ $stats['coursesInProgress'] }} cursos en progreso.</p>
                            
                            @if($stats['todayEvents'] > 0)
                                <div class="mt-3">
                                    <a href="{{ route('student.calendar') }}" class="welcome-btn">
                                        <i class="fas fa-calendar-day"></i> Tienes {{ $stats['todayEvents'] }} eventos hoy
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 d-none d-md-block">
                            <i class="fas fa-laptop-code welcome-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-primary">
                <div class="stats-card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-card-title stats-card-title-primary">
                                Cursos Totales</div>
                            <div class="stats-card-value">{{ $stats['totalCourses'] }}</div>
                        </div>
                        <i class="fas fa-book stats-card-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-success">
                <div class="stats-card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-card-title stats-card-title-success">
                                Cursos Completados</div>
                            <div class="stats-card-value">{{ $stats['completedCourses'] }}</div>
                        </div>
                        <i class="fas fa-check-circle stats-card-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-info">
                <div class="stats-card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-card-title stats-card-title-info">
                                Progreso Promedio</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="stats-card-value">{{ $stats['averageProgress'] }}%</div>
                                </div>
                                <div class="col">
                                    <div class="course-card-dashboard-progress-bar mt-2">
                                        <div class="course-card-dashboard-progress-fill" style="width: {{ $stats['averageProgress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <i class="fas fa-clipboard-list stats-card-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card stats-card-warning">
                <div class="stats-card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="stats-card-title stats-card-title-warning">
                                Eventos Pendientes</div>
                            <div class="stats-card-value">{{ $stats['upcomingEvents'] }}</div>
                        </div>
                        <i class="fas fa-calendar stats-card-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Cursos en progreso -->
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h6 class="content-card-title">Mis Cursos</h6>
                    <a href="{{ route('student.courses') }}" class="btn btn-sm btn-primary">Ver todos</a>
                </div>
                <div class="content-card-body">
                    @if($inProgressCourses->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-book-open empty-state-icon"></i>
                            <p class="empty-state-text">No tienes cursos en progreso</p>
                            <a href="{{ route('courses.index') }}" class="empty-state-btn">Explorar cursos</a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($inProgressCourses as $enrollment)
                                <div class="col-md-6 mb-4">
                                    <div class="course-card-dashboard">
                                        <div class="position-relative">
                                            @if($enrollment->course->image)
                                                <img src="{{ asset('storage/' . $enrollment->course->image) }}" class="course-card-dashboard-img" alt="{{ $enrollment->course->title }}">
                                            @else
                                                <img src="{{ asset('images/course-placeholder.jpg') }}" class="course-card-dashboard-img" alt="{{ $enrollment->course->title }}">
                                            @endif
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <span class="badge bg-primary">{{ $enrollment->progress }}%</span>
                                            </div>
                                        </div>
                                        <div class="course-card-dashboard-body">
                                            <h5 class="course-card-dashboard-title">{{ $enrollment->course->title }}</h5>
                                            <div class="course-card-dashboard-progress">
                                                <div class="course-card-dashboard-progress-text">
                                                    <span>Progreso</span>
                                                    <span>{{ $enrollment->progress }}%</span>
                                                </div>
                                                <div class="course-card-dashboard-progress-bar">
                                                    <div class="course-card-dashboard-progress-fill" style="width: {{ $enrollment->progress }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="course-card-dashboard-footer">
                                            <a href="{{ route('student.courses.progress', $enrollment->course->id) }}" class="course-card-dashboard-link">
                                                <i class="fas fa-arrow-right"></i> Continuar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna lateral -->
        <div class="col-lg-4">
            <!-- Próximos eventos -->
            <div class="content-card">
                <div class="content-card-header">
                    <h6 class="content-card-title">Próximos Eventos</h6>
                    <a href="{{ route('student.calendar') }}" class="btn btn-sm btn-primary">Ver calendario</a>
                </div>
                <div class="content-card-body">
                    @if($upcomingEvents->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt empty-state-icon"></i>
                            <p class="empty-state-text">No tienes eventos próximos</p>
                        </div>
                    @else
                        <ul class="event-list">
                            @foreach($upcomingEvents as $event)
                                <li class="event-item">
                                    <div class="event-date">
                                        <div class="event-day">{{ $event->start_date->format('d') }}</div>
                                        <div class="event-month">{{ $event->start_date->format('M') }}</div>
                                    </div>
                                    <div class="event-content">
                                        <h6 class="event-title">{{ $event->title }}</h6>
                                        <div class="event-info">
                                            <span class="event-info-item">
                                                <i class="fas fa-clock"></i> {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}
                                            </span>
                                            @if($event->location)
                                                <span class="event-info-item">
                                                    <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Notificaciones recientes -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Notificaciones</h6>
                    <a href="{{ route('student.notifications') }}" class="btn btn-sm btn-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    @if(auth()->user()->notifications->isEmpty())
                        <div class="text-center py-3">
                            <i class="fas fa-bell fa-2x text-gray-300 mb-3"></i>
                            <p class="mb-0">No tienes notificaciones</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach(auth()->user()->notifications()->take(5)->get() as $notification)
                                <a href="{{ route('student.notifications.show', $notification) }}" class="list-group-item list-group-item-action px-3 py-2 {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                                    <div class="d-flex w-100 align-items-center">
                                        <div class="notification-icon me-3">
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
                                        <div>
                                            <p class="mb-1">{{ Str::limit($notification->data['message'], 50) }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tareas pendientes -->
            <div class="content-card">
                <div class="content-card-header">
                    <h6 class="content-card-title">Tareas Pendientes</h6>
                    <a href="{{ route('student.tasks') }}" class="btn btn-sm btn-primary">Ver todas</a>
                </div>
                <div class="content-card-body">
                    @if($pendingTasks->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-tasks empty-state-icon"></i>
                            <p class="empty-state-text">No tienes tareas pendientes</p>
                        </div>
                    @else
                        <ul class="task-list">
                            @foreach($pendingTasks as $task)
                                <li class="task-item">
                                    <div class="task-checkbox-wrapper">
                                        <input class="form-check-input task-checkbox" type="checkbox" value="{{ $task->id }}" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                                        <div class="task-content">
                                            <div class="task-title">{{ $task->title }}</div>
                                            @if($task->due_date)
                                                <div class="task-due-date">
                                                    <i class="fas fa-calendar-alt"></i> Vence: {{ $task->due_date->format('d M, Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="task-priority task-priority-{{ strtolower($task->priority) }}">{{ $task->priority }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Marcar tareas como completadas
        const taskCheckboxes = document.querySelectorAll('.task-checkbox');
        taskCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const taskId = this.getAttribute('data-task-id');
                if (this.checked) {
                    // Aquí iría el código para marcar la tarea como completada
                    // mediante una petición AJAX
                    fetch(`/student/tasks/${taskId}/complete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Eliminar visualmente la tarea después de un momento
                            setTimeout(() => {
                                this.closest('.list-group-item').remove();
                                
                                // Si no quedan tareas, mostrar mensaje
                                if (document.querySelectorAll('.task-checkbox').length === 0) {
                                    const listGroup = document.querySelector('.list-group');
                                    listGroup.innerHTML = `
                                        <div class="text-center py-3">
                                            <i class="fas fa-tasks fa-2x text-gray-300 mb-3"></i>
                                            <p class="mb-0">No tienes tareas pendientes</p>
                                        </div>
                                    `;
                                }
                            }, 500);
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
