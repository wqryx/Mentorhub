@extends('layouts.dashboard.student')

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">¡Bienvenido, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0">Continúa con tu aprendizaje. Tienes {{ $stats['coursesInProgress'] }} cursos en progreso.</p>
                            
                            @if($stats['todayEvents'] > 0)
                                <div class="mt-3">
                                    <a href="{{ route('student.calendar') }}" class="btn btn-light">
                                        <i class="fas fa-calendar-day me-1"></i> Tienes {{ $stats['todayEvents'] }} eventos hoy
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 d-none d-md-block text-end">
                            <i class="fas fa-laptop-code fa-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Cursos Totales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalCourses'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Cursos Completados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completedCourses'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Progreso Promedio</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $stats['averageProgress'] }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: {{ $stats['averageProgress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Eventos Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pendingEvents'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Cursos en progreso -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Cursos</h6>
                    <a href="{{ route('student.courses') }}" class="btn btn-sm btn-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    @if($inProgressCourses->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-gray-300 mb-3"></i>
                            <p class="mb-0">No tienes cursos en progreso</p>
                            <a href="{{ route('courses.index') }}" class="btn btn-primary mt-3">Explorar cursos</a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($inProgressCourses as $enrollment)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="position-relative">
                                            @if($enrollment->course->image)
                                                <img src="{{ asset('storage/' . $enrollment->course->image) }}" class="card-img-top" alt="{{ $enrollment->course->title }}" style="height: 140px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('images/default-course.jpg') }}" class="card-img-top" alt="{{ $enrollment->course->title }}" style="height: 140px; object-fit: cover;">
                                            @endif
                                            <div class="position-absolute top-0 end-0 p-2">
                                                @if($enrollment->course->is_premium)
                                                    <span class="badge bg-warning"><i class="fas fa-crown"></i></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body pb-0">
                                            <h5 class="card-title">{{ Str::limit($enrollment->course->title, 40) }}</h5>
                                            
                                            <!-- Progreso -->
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                                    <span>Progreso</span>
                                                    <span>{{ $enrollment->progress }}%</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enrollment->progress }}%;" aria-valuenow="{{ $enrollment->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top-0">
                                            <div class="d-grid">
                                                @if($enrollment->currentTutorial)
                                                    <a href="{{ route('courses.modules.tutorials.show', [
                                                        $enrollment->course,
                                                        $enrollment->currentTutorial->module,
                                                        $enrollment->currentTutorial
                                                    ]) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-play me-1"></i> Continuar
                                                    </a>
                                                @else
                                                    <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-book-open me-1"></i> Ver curso
                                                    </a>
                                                @endif
                                            </div>
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
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Próximos Eventos</h6>
                    <a href="{{ route('student.calendar') }}" class="btn btn-sm btn-primary">Ver calendario</a>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->isEmpty())
                        <div class="text-center py-3">
                            <i class="fas fa-calendar fa-2x text-gray-300 mb-3"></i>
                            <p class="mb-0">No tienes eventos próximos</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($upcomingEvents as $event)
                                <div class="list-group-item px-0">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $event->title }}</h6>
                                            <p class="mb-1 small text-muted">
                                                <i class="fas fa-calendar-day me-1"></i> {{ $event->start_time->format('d M, Y') }}
                                                <br>
                                                <i class="fas fa-clock me-1"></i> {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}
                                            </p>
                                        </div>
                                        <span class="badge bg-{{ $event->type_color }}">{{ $event->type }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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
            <div class="card shadow-sm">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tareas Pendientes</h6>
                    <a href="{{ route('student.tasks') }}" class="btn btn-sm btn-primary">Ver todas</a>
                </div>
                <div class="card-body">
                    @if($pendingTasks->isEmpty())
                        <div class="text-center py-3">
                            <i class="fas fa-tasks fa-2x text-gray-300 mb-3"></i>
                            <p class="mb-0">No tienes tareas pendientes</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($pendingTasks as $task)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input task-checkbox" type="checkbox" value="{{ $task->id }}" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                                            <label class="form-check-label" for="task-{{ $task->id }}">
                                                {{ $task->title }}
                                            </label>
                                        </div>
                                        @if($task->due_date)
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i> Vence: {{ $task->due_date->format('d M, Y') }}
                                            </small>
                                        @endif
                                    </div>
                                    <span class="badge bg-{{ $task->priority_color }}">{{ $task->priority }}</span>
                                </div>
                            @endforeach
                        </div>
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
