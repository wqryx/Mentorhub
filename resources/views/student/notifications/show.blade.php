@extends('layouts.student')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/student-notifications.css') }}">
@endpush

@section('dashboard-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Detalle de Notificación</h6>
                    <a href="{{ route('student.notifications') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> Volver a notificaciones
                    </a>
                </div>
                <div class="card-body">
                    <div class="notification-detail">
                        <div class="notification-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="notification-icon me-3">
                                    @if($notification->type == 'App\Notifications\CourseEnrollment')
                                        <i class="fas fa-book-open fa-2x text-primary"></i>
                                    @elseif($notification->type == 'App\Notifications\NewCourseContent')
                                        <i class="fas fa-plus-circle fa-2x text-success"></i>
                                    @elseif($notification->type == 'App\Notifications\CourseCompleted')
                                        <i class="fas fa-trophy fa-2x text-warning"></i>
                                    @else
                                        <i class="fas fa-bell fa-2x text-info"></i>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $notification->data['title'] ?? 'Notificación' }}</h5>
                                    <p class="text-muted mb-0">
                                        <small>
                                            <i class="fas fa-clock me-1"></i> {{ $notification->created_at->format('d M, Y H:i') }}
                                            @if($notification->read_at)
                                                <span class="ms-2 text-success">
                                                    <i class="fas fa-check-circle me-1"></i> Leída el {{ $notification->read_at->format('d M, Y H:i') }}
                                                </span>
                                            @endif
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="notification-content mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $notification->data['message'] ?? 'No hay mensaje disponible' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($notification->data['action_url']) && isset($notification->data['action_text']))
                            <div class="notification-action mb-4">
                                <a href="{{ $notification->data['action_url'] }}" class="btn btn-primary">
                                    {{ $notification->data['action_text'] }}
                                </a>
                            </div>
                        @endif
                        
                        @if(isset($notification->data['additional_data']))
                            <div class="notification-additional-data">
                                <h6 class="mb-3">Información adicional</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            @foreach($notification->data['additional_data'] as $key => $value)
                                                <tr>
                                                    <th style="width: 30%">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                    <td>{{ $value }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
