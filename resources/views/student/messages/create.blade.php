@extends('layouts.student')

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Nuevo mensaje</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('student.messages.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver a mensajes
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> Por favor corrige los siguientes errores:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Redactar mensaje</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.messages.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient_id" class="form-label">Destinatario</label>
                            <select class="form-select @error('recipient_id') is-invalid @enderror" id="recipient_id" name="recipient_id" required>
                                <option value="">Seleccionar destinatario</option>
                                <optgroup label="Mentores">
                                    @foreach($recipients->where('roles.name', 'mentor') as $mentor)
                                        <option value="{{ $mentor->id }}" {{ old('recipient_id') == $mentor->id ? 'selected' : '' }}>
                                            {{ $mentor->name }} - Mentor
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Administradores">
                                    @foreach($recipients->where('roles.name', 'admin') as $admin)
                                        <option value="{{ $admin->id }}" {{ old('recipient_id') == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }} - Administrador
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('recipient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Asunto</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required maxlength="255">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Mensaje</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('student.messages.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Enviar mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el select con búsqueda
        const recipientSelect = document.getElementById('recipient_id');
        
        // Aquí podrías agregar código para convertir el select en un componente de búsqueda
        // Por ejemplo, usando Select2 o similar si está disponible en el proyecto
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/student-messages.css') }}">
@endpush
