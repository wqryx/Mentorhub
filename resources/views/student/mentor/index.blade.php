@extends('layouts.student')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mentor.css') }}">
@endpush

@section('dashboard-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mi Mentor</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
    
    @if($mentor)
        <div class="mentor-profile-card">
            <div class="mentor-header">
                <img class="mentor-avatar" src="{{ $mentor->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ $mentor->name }}">
                <div class="mentor-info">
                    <h2 class="mentor-name">{{ $mentor->name }}</h2>
                    <p class="mentor-email">{{ $mentor->email }}</p>
                    <p class="mentor-bio">{{ $mentor->profile->bio ?? 'No hay biografía disponible' }}</p>
                </div>
            </div>
            
            <div class="specialties-section">
                <h3 class="section-title">Especialidades</h3>
                <div class="specialty-tags">
                    @forelse($mentor->specialties ?? [] as $specialty)
                        <span class="specialty-tag">
                            {{ $specialty->name }}
                        </span>
                    @empty
                        <p class="text-muted">No hay especialidades registradas.</p>
                    @endforelse
                </div>
            </div>
            
            <div class="contact-section">
                <h3 class="section-title">Contacto</h3>
                <div class="contact-details">
                    <p class="contact-item"><span class="contact-label">Email:</span> <span class="contact-value">{{ $mentor->email }}</span></p>
                    @if($mentor->profile && $mentor->profile->phone)
                        <p class="contact-item"><span class="contact-label">Teléfono:</span> <span class="contact-value">{{ $mentor->profile->phone }}</span></p>
                    @endif
                    @if($mentor->profile && $mentor->profile->linkedin_url)
                        <p class="contact-item">
                            <span class="contact-label">LinkedIn:</span> 
                            <a href="{{ $mentor->profile->linkedin_url }}" target="_blank" class="contact-link">
                                {{ $mentor->profile->linkedin_url }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="next-session-section">
                <h3 class="section-title">Próxima Sesión</h3>
                @php
                    $nextSession = \App\Models\Event::where('mentor_id', $mentor->id)
                        ->where('start_date', '>=', now())
                        ->orderBy('start_date')
                        ->first();
                @endphp
                
                @if($nextSession)
                    <div class="session-card">
                        <p class="session-title">{{ $nextSession->title }}</p>
                        <p class="session-date">{{ \Carbon\Carbon::parse($nextSession->start_date)->format('d/m/Y H:i') }}</p>
                        <p class="session-description">{{ $nextSession->description }}</p>
                        <a href="{{ route('student.calendar') }}" class="calendar-link">
                            <i class="fas fa-calendar-alt"></i> Ver en calendario
                        </a>
                    </div>
                @else
                    <p class="text-muted">No hay sesiones programadas.</p>
                @endif
            </div>
        </div>
        
        <div class="message-card">
            <div class="card-header">
                <h2 class="card-title">Enviar Mensaje</h2>
            </div>
            <div class="message-form">
                <form action="#" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="subject" class="form-label">Asunto</label>
                        <input type="text" name="subject" id="subject" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea name="message" id="message" rows="4" class="form-control"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Enviar Mensaje
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="no-mentor-alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle fa-2x"></i>
            </div>
            <div class="alert-content">
                <p>
                    No tienes un mentor asignado actualmente. Por favor, contacta con el administrador para que te asigne un mentor.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
