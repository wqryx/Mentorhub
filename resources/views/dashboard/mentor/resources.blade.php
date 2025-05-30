@extends('layouts.mentor')

@section('title', 'Recursos Educativos - MentorHub')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Recursos Educativos</h1>
        <div>
            <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                <i class="fas fa-plus-circle fa-sm text-white-50"></i> Nuevo Recurso
            </button>
        </div>
    </div>
    
    @include('partials.alerts')
    
    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary active" data-filter="all">Todos</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-filter="document">Documentos</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-filter="video">Videos</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-filter="link">Enlaces</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-filter="exercise">Ejercicios</button>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="showSharedOnly">
                            <label class="form-check-label" for="showSharedOnly">Solo compartidos</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="resourceSearch" placeholder="Buscar recursos...">
                <button class="btn btn-outline-secondary" type="button" id="searchResourceBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Categorías de recursos -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Documentos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Videos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-video fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Enlaces</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">15</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-link fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ejercicios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenido principal -->
    <div class="row" id="resourcesContainer">
        <!-- Documentos -->
        <div class="col-md-4 mb-4 resource-item" data-type="document">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Introducción a HTML5</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink1">
                            <a class="dropdown-item" href="#"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-share fa-sm fa-fw mr-2 text-gray-400"></i> Compartir</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Eliminar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary text-white mr-3">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <small class="text-muted">PDF - 2.5 MB</small>
                        </div>
                    </div>
                    <p class="card-text">Guía completa sobre los fundamentos de HTML5 para principiantes. Incluye ejemplos prácticos y ejercicios.</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="badge bg-primary">HTML</span>
                            <span class="badge bg-secondary">Frontend</span>
                        </div>
                        <small class="text-muted">Subido: 28/05/2025</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download mr-1"></i> Descargar</button>
                    <div>
                        <span class="text-success mr-2" data-bs-toggle="tooltip" title="Compartido con estudiantes">
                            <i class="fas fa-users"></i> 8
                        </span>
                        <span class="text-info" data-bs-toggle="tooltip" title="Descargas">
                            <i class="fas fa-download"></i> 12
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Videos -->
        <div class="col-md-4 mb-4 resource-item" data-type="video">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">JavaScript Asincrónico</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink2">
                            <a class="dropdown-item" href="#"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-share fa-sm fa-fw mr-2 text-gray-400"></i> Compartir</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Eliminar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="video-thumbnail position-relative mb-3">
                        <img src="https://img.youtube.com/vi/8aGhZQkoFbQ/maxresdefault.jpg" class="img-fluid rounded" alt="JavaScript Event Loop">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <button class="btn btn-light rounded-circle">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    <p class="card-text">Tutorial sobre promesas, async/await y el bucle de eventos en JavaScript. Incluye ejemplos prácticos.</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="badge bg-success">JavaScript</span>
                            <span class="badge bg-secondary">Avanzado</span>
                        </div>
                        <small class="text-muted">Subido: 15/05/2025</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-success"><i class="fas fa-play mr-1"></i> Ver video</button>
                    <div>
                        <span class="text-success mr-2" data-bs-toggle="tooltip" title="Compartido con estudiantes">
                            <i class="fas fa-users"></i> 5
                        </span>
                        <span class="text-info" data-bs-toggle="tooltip" title="Visualizaciones">
                            <i class="fas fa-eye"></i> 18
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Enlaces -->
        <div class="col-md-4 mb-4 resource-item" data-type="link">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">MDN Web Docs - CSS Grid</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink3">
                            <a class="dropdown-item" href="#"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-share fa-sm fa-fw mr-2 text-gray-400"></i> Compartir</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Eliminar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-info text-white mr-3">
                            <i class="fas fa-link"></i>
                        </div>
                        <div>
                            <small class="text-muted">developer.mozilla.org</small>
                        </div>
                    </div>
                    <p class="card-text">Documentación completa sobre CSS Grid Layout. Recurso oficial de Mozilla Developer Network con ejemplos interactivos.</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="badge bg-info">CSS</span>
                            <span class="badge bg-secondary">Layout</span>
                        </div>
                        <small class="text-muted">Añadido: 20/05/2025</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <a href="https://developer.mozilla.org/es/docs/Web/CSS/CSS_Grid_Layout" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-external-link-alt mr-1"></i> Visitar</a>
                    <div>
                        <span class="text-success mr-2" data-bs-toggle="tooltip" title="Compartido con estudiantes">
                            <i class="fas fa-users"></i> 10
                        </span>
                        <span class="text-info" data-bs-toggle="tooltip" title="Clics">
                            <i class="fas fa-mouse-pointer"></i> 25
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ejercicios -->
        <div class="col-md-4 mb-4 resource-item" data-type="exercise">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">Ejercicios de React Hooks</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink4">
                            <a class="dropdown-item" href="#"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-share fa-sm fa-fw mr-2 text-gray-400"></i> Compartir</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Eliminar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-warning text-white mr-3">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <small class="text-muted">5 ejercicios - Nivel intermedio</small>
                        </div>
                    </div>
                    <p class="card-text">Conjunto de ejercicios prácticos para dominar los hooks de React (useState, useEffect, useContext, etc).</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="badge bg-warning">React</span>
                            <span class="badge bg-secondary">Hooks</span>
                        </div>
                        <small class="text-muted">Creado: 10/05/2025</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-warning"><i class="fas fa-code mr-1"></i> Ver ejercicios</button>
                    <div>
                        <span class="text-success mr-2" data-bs-toggle="tooltip" title="Compartido con estudiantes">
                            <i class="fas fa-users"></i> 6
                        </span>
                        <span class="text-info" data-bs-toggle="tooltip" title="Completados">
                            <i class="fas fa-check-circle"></i> 4
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Documento -->
        <div class="col-md-4 mb-4 resource-item" data-type="document">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Guía de SQL Avanzado</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink5" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink5">
                            <a class="dropdown-item" href="#"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-share fa-sm fa-fw mr-2 text-gray-400"></i> Compartir</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Eliminar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary text-white mr-3">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <div>
                            <small class="text-muted">DOCX - 1.8 MB</small>
                        </div>
                    </div>
                    <p class="card-text">Documento con técnicas avanzadas de SQL, optimización de consultas y ejemplos de casos reales.</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="badge bg-primary">SQL</span>
                            <span class="badge bg-secondary">Base de Datos</span>
                        </div>
                        <small class="text-muted">Subido: 05/05/2025</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download mr-1"></i> Descargar</button>
                    <div>
                        <span class="text-success mr-2" data-bs-toggle="tooltip" title="Compartido con estudiantes">
                            <i class="fas fa-users"></i> 4
                        </span>
                        <span class="text-info" data-bs-toggle="tooltip" title="Descargas">
                            <i class="fas fa-download"></i> 9
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Video -->
        <div class="col-md-4 mb-4 resource-item" data-type="video">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">Introducción a Docker</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink6">
                            <a class="dropdown-item" href="#"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-share fa-sm fa-fw mr-2 text-gray-400"></i> Compartir</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Eliminar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="video-thumbnail position-relative mb-3">
                        <img src="https://i.ytimg.com/vi/gAkwW2tuIqE/maxresdefault.jpg" class="img-fluid rounded" alt="Docker Tutorial">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <button class="btn btn-light rounded-circle">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    <p class="card-text">Tutorial completo sobre Docker para principiantes. Aprende a crear, gestionar y desplegar contenedores.</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="badge bg-success">Docker</span>
                            <span class="badge bg-secondary">DevOps</span>
                        </div>
                        <small class="text-muted">Subido: 18/05/2025</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-success"><i class="fas fa-play mr-1"></i> Ver video</button>
                    <div>
                        <span class="text-success mr-2" data-bs-toggle="tooltip" title="Compartido con estudiantes">
                            <i class="fas fa-users"></i> 3
                        </span>
                        <span class="text-info" data-bs-toggle="tooltip" title="Visualizaciones">
                            <i class="fas fa-eye"></i> 7
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar recurso -->
<div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResourceModalLabel">Añadir Nuevo Recurso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="resourceForm">
                    <div class="mb-3">
                        <label for="resourceTitle" class="form-label">Título del Recurso</label>
                        <input type="text" class="form-control" id="resourceTitle" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resourceType" class="form-label">Tipo de Recurso</label>
                        <select class="form-select" id="resourceType" required>
                            <option value="" selected disabled>Seleccionar tipo...</option>
                            <option value="document">Documento</option>
                            <option value="video">Video</option>
                            <option value="link">Enlace</option>
                            <option value="exercise">Ejercicio</option>
                        </select>
                    </div>
                    
                    <div id="dynamicFields">
                        <!-- Los campos se cargarán dinámicamente según el tipo seleccionado -->
                    </div>
                    
                    <div class="mb-3">
                        <label for="resourceDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="resourceDescription" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resourceTags" class="form-label">Etiquetas (separadas por comas)</label>
                        <input type="text" class="form-control" id="resourceTags" placeholder="HTML, CSS, Frontend">
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="shareWithStudents">
                        <label class="form-check-label" for="shareWithStudents">
                            Compartir con estudiantes
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveResourceBtn">Guardar Recurso</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Filtrar recursos por tipo
        const filterButtons = document.querySelectorAll('[data-filter]');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Actualizar estado activo de los botones
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                const resourceItems = document.querySelectorAll('.resource-item');
                
                resourceItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-type') === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Filtrar recursos compartidos
        const sharedOnlyCheckbox = document.getElementById('showSharedOnly');
        sharedOnlyCheckbox.addEventListener('change', function() {
            // Implementar lógica para mostrar solo recursos compartidos
            // Esta es una simulación, en producción se conectaría con datos reales
            const resourceItems = document.querySelectorAll('.resource-item');
            if (this.checked) {
                resourceItems.forEach((item, index) => {
                    // Simulación: ocultar algunos elementos aleatoriamente
                    if (index % 2 === 0) {
                        item.style.display = 'none';
                    }
                });
            } else {
                // Restaurar visibilidad según el filtro activo
                const activeFilter = document.querySelector('[data-filter].active').getAttribute('data-filter');
                resourceItems.forEach(item => {
                    if (activeFilter === 'all' || item.getAttribute('data-type') === activeFilter) {
                        item.style.display = 'block';
                    }
                });
            }
        });
        
        // Búsqueda de recursos
        const searchInput = document.getElementById('resourceSearch');
        const searchButton = document.getElementById('searchResourceBtn');
        
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            const resourceItems = document.querySelectorAll('.resource-item');
            
            resourceItems.forEach(item => {
                const title = item.querySelector('.card-header h6').textContent.toLowerCase();
                const description = item.querySelector('.card-text').textContent.toLowerCase();
                const tags = Array.from(item.querySelectorAll('.badge')).map(tag => tag.textContent.toLowerCase());
                
                if (title.includes(searchTerm) || description.includes(searchTerm) || tags.some(tag => tag.includes(searchTerm))) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        searchButton.addEventListener('click', performSearch);
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        });
        
        // Manejar cambio de tipo de recurso en el modal
        const resourceTypeSelect = document.getElementById('resourceType');
        const dynamicFieldsContainer = document.getElementById('dynamicFields');
        
        resourceTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            dynamicFieldsContainer.innerHTML = ''; // Limpiar campos anteriores
            
            switch(selectedType) {
                case 'document':
                    dynamicFieldsContainer.innerHTML = `
                        <div class="mb-3">
                            <label for="documentFile" class="form-label">Archivo</label>
                            <input type="file" class="form-control" id="documentFile" required>
                        </div>
                    `;
                    break;
                case 'video':
                    dynamicFieldsContainer.innerHTML = `
                        <div class="mb-3">
                            <label for="videoType" class="form-label">Tipo de Video</label>
                            <select class="form-select" id="videoType" required>
                                <option value="upload">Subir video</option>
                                <option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>
                            </select>
                        </div>
                        <div class="mb-3" id="videoSourceContainer">
                            <label for="videoFile" class="form-label">Archivo de Video</label>
                            <input type="file" class="form-control" id="videoFile" required>
                        </div>
                    `;
                    
                    // Manejar cambio de tipo de video
                    setTimeout(() => {
                        const videoTypeSelect = document.getElementById('videoType');
                        const videoSourceContainer = document.getElementById('videoSourceContainer');
                        
                        videoTypeSelect.addEventListener('change', function() {
                            const selectedVideoType = this.value;
                            
                            if (selectedVideoType === 'upload') {
                                videoSourceContainer.innerHTML = `
                                    <label for="videoFile" class="form-label">Archivo de Video</label>
                                    <input type="file" class="form-control" id="videoFile" required>
                                `;
                            } else {
                                videoSourceContainer.innerHTML = `
                                    <label for="videoUrl" class="form-label">URL del Video</label>
                                    <input type="url" class="form-control" id="videoUrl" placeholder="https://" required>
                                `;
                            }
                        });
                    }, 100);
                    break;
                case 'link':
                    dynamicFieldsContainer.innerHTML = `
                        <div class="mb-3">
                            <label for="linkUrl" class="form-label">URL</label>
                            <input type="url" class="form-control" id="linkUrl" placeholder="https://" required>
                        </div>
                        <div class="mb-3">
                            <label for="linkSite" class="form-label">Nombre del Sitio</label>
                            <input type="text" class="form-control" id="linkSite" placeholder="Mozilla Developer Network">
                        </div>
                    `;
                    break;
                case 'exercise':
                    dynamicFieldsContainer.innerHTML = `
                        <div class="mb-3">
                            <label for="exerciseCount" class="form-label">Número de Ejercicios</label>
                            <input type="number" class="form-control" id="exerciseCount" min="1" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="exerciseLevel" class="form-label">Nivel</label>
                            <select class="form-select" id="exerciseLevel" required>
                                <option value="beginner">Principiante</option>
                                <option value="intermediate">Intermedio</option>
                                <option value="advanced">Avanzado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exerciseFile" class="form-label">Archivo de Ejercicios (opcional)</label>
                            <input type="file" class="form-control" id="exerciseFile">
                        </div>
                    `;
                    break;
            }
        });
        
        // Manejar guardado de recurso
        const saveResourceBtn = document.getElementById('saveResourceBtn');
        saveResourceBtn.addEventListener('click', function() {
            // Validar formulario
            const form = document.getElementById('resourceForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Aquí iría la lógica para guardar el recurso
            // En producción, esto enviaría los datos al servidor
            
            // Simular éxito y cerrar modal
            alert('Recurso guardado correctamente');
            const modal = bootstrap.Modal.getInstance(document.getElementById('addResourceModal'));
            modal.hide();
        });
    });
</script>
@endsection
