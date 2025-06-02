@extends('layouts.student')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/student-notifications.css') }}">
@endpush

@section('dashboard-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Notificaciones</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <a href="{{ route('student.notifications.mark-all-read') }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-check-double me-1"></i> Marcar todas como leídas
                        </a>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @if($notifications->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-bell empty-state-icon"></i>
                            <p class="empty-state-text">No tienes notificaciones</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <a href="{{ route('student.notifications.show', $notification) }}" class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'unread' }}">
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
                                        <div class="flex-grow-1">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $notification->data['title'] ?? 'Notificación' }}</h5>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ $notification->data['message'] ?? 'No hay mensaje disponible' }}</p>
                                            @if(!$notification->read_at)
                                                <span class="badge bg-primary">Nueva</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
