@extends('layouts.student')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/student-courses.css') }}">
@endpush

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mis Cursos</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-search me-1"></i> Explorar más cursos
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('student.courses') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Todos</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En progreso</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completados</option>
                                <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>No iniciados</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="sort" class="form-label">Ordenar por</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Más recientes</option>
                                <option value="progress" {{ request('sort') == 'progress' ? 'selected' : '' }}>Mayor progreso</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Título (A-Z)</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cursos en progreso -->
    @if($inProgressCourses->count() > 0)
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="mb-3">Continúa aprendiendo</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($inProgressCourses as $enrollment)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            @if($enrollment->course->image)
                                <img src="{{ asset('storage/' . $enrollment->course->image) }}" class="card-img-top" alt="{{ $enrollment->course->title }}">
                            @else
                                <img src="{{ asset('images/default-course.jpg') }}" class="card-img-top" alt="{{ $enrollment->course->title }}">
                            @endif
                            <div class="position-absolute top-0 end-0 p-2">
                                @if($enrollment->course->is_premium)
                                    <span class="badge bg-warning"><i class="fas fa-crown"></i> Premium</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <h5 class="card-title">{{ $enrollment->course->title }}</h5>
                            <p class="card-text small text-muted">
                                <i class="fas fa-book me-1"></i> {{ $enrollment->course->modules->count() }} módulos
                                <i class="fas fa-video ms-2 me-1"></i> {{ $enrollment->course->modules->flatMap->tutorials->count() }} tutoriales
                            </p>
                            
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

                            <!-- Último acceso -->
                            @if($enrollment->last_activity_at)
                                <p class="card-text small text-muted">
                                    <i class="fas fa-clock me-1"></i> Último acceso: {{ $enrollment->last_activity_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-grid gap-2">
                                @if($enrollment->currentTutorial)
                                    <a href="{{ route('courses.modules.tutorials.show', [
                                        $enrollment->course,
                                        $enrollment->currentTutorial->module,
                                        $enrollment->currentTutorial
                                    ]) }}" class="btn btn-primary">
                                        <i class="fas fa-play me-1"></i> Continuar
                                    </a>
                                @else
                                    <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-primary">
                                        <i class="fas fa-book-open me-1"></i> Ver curso
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Todos los cursos -->
    <div class="row">
        <div class="col-md-12">
            @if($enrollments->isEmpty())
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">¡Aún no estás inscrito en ningún curso!</h4>
                    <p>Explora nuestro catálogo de cursos y comienza tu aprendizaje hoy mismo.</p>
                    <hr>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Explorar cursos
                        </a>
                    </div>
                </div>
            @else
                <h4 class="mb-3">Todos mis cursos</h4>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach($enrollments as $enrollment)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                @if($enrollment->course->image)
                                    <img src="{{ asset('storage/' . $enrollment->course->image) }}" class="card-img-top" alt="{{ $enrollment->course->title }}">
                                @else
                                    <img src="{{ asset('images/default-course.jpg') }}" class="card-img-top" alt="{{ $enrollment->course->title }}">
                                @endif
                                <div class="position-absolute top-0 end-0 p-2">
                                    @if($enrollment->course->is_premium)
                                        <span class="badge bg-warning"><i class="fas fa-crown"></i> Premium</span>
                                    @endif
                                    
                                    @if($enrollment->progress == 100)
                                        <span class="badge bg-success ms-1"><i class="fas fa-check-circle"></i> Completado</span>
                                    @elseif($enrollment->progress == 0)
                                        <span class="badge bg-secondary ms-1"><i class="fas fa-hourglass-start"></i> No iniciado</span>
                                    @else
                                        <span class="badge bg-primary ms-1"><i class="fas fa-spinner"></i> En progreso</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <h5 class="card-title">{{ $enrollment->course->title }}</h5>
                                <p class="card-text small text-muted mb-2">
                                    @if($enrollment->course->level)
                                        <span class="me-2">
                                            <i class="fas fa-signal me-1"></i> 
                                            @if($enrollment->course->level == 'beginner')
                                                Principiante
                                            @elseif($enrollment->course->level == 'intermediate')
                                                Intermedio
                                            @elseif($enrollment->course->level == 'advanced')
                                                Avanzado
                                            @endif
                                        </span>
                                    @endif
                                    <span><i class="fas fa-clock me-1"></i> {{ $enrollment->course->duration_hours }} horas</span>
                                </p>
                                
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

                                <!-- Fecha de inscripción -->
                                <p class="card-text small text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i> Inscrito el: {{ $enrollment->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-grid gap-2">
                                    @if($enrollment->progress == 0)
                                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-primary">
                                            <i class="fas fa-play me-1"></i> Comenzar curso
                                        </a>
                                    @elseif($enrollment->progress == 100)
                                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-redo me-1"></i> Repasar curso
                                        </a>
                                    @else
                                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-primary">
                                            <i class="fas fa-book-open me-1"></i> Continuar curso
                                        </a>
                                    @endif
                                    <a href="{{ route('student.course.progress', $enrollment->course) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-chart-line me-1"></i> Ver progreso
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $enrollments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
