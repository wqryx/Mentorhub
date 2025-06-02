@extends('layouts.student')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/student-profile.css') }}">
@endpush

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mi Perfil</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información del perfil -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->profile && $user->profile->avatar)
                            <img src="{{ asset('storage/' . $user->profile->avatar) }}" class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;" alt="Avatar">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                <span class="text-white display-4">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <p class="text-muted">
                        <span class="badge bg-primary">Estudiante</span>
                    </p>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changeAvatarModal">
                            <i class="fas fa-camera me-1"></i> Cambiar avatar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Información de contacto -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Información de contacto</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-envelope me-2 text-primary"></i> Email</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-phone me-2 text-primary"></i> Teléfono</span>
                            <span>{{ $user->profile->phone ?? 'No especificado' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-map-marker-alt me-2 text-primary"></i> Ubicación</span>
                            <span>{{ $user->profile->location ?? 'No especificada' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Formulario de edición de perfil -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Editar Perfil</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                                <small class="text-muted">El email no puede ser modificado</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->profile->phone ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="location" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ $user->profile->location ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Biografía</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4">{{ $user->profile->bio ?? '' }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="interests" class="form-label">Intereses</label>
                                <input type="text" class="form-control" id="interests" name="interests" value="{{ $user->profile->interests ?? '' }}">
                                <small class="text-muted">Separados por comas</small>
                            </div>
                            <div class="col-md-6">
                                <label for="education" class="form-label">Educación</label>
                                <input type="text" class="form-control" id="education" name="education" value="{{ $user->profile->education ?? '' }}">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cambiar contraseña -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Cambiar Contraseña</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña actual</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-1"></i> Actualizar contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar avatar -->
<div class="modal fade" id="changeAvatarModal" tabindex="-1" aria-labelledby="changeAvatarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeAvatarModalLabel">Cambiar Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Seleccionar imagen</label>
                        <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Subir avatar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
