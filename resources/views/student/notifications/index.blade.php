@extends('student.layouts.app')

@section('title', 'Notificaciones - MentorHub')

@push('styles')
<style>
    .notification-item {
        @apply transition-colors duration-200 hover:bg-gray-50;
    }
    .notification-unread {
        @apply bg-blue-50 border-l-4 border-blue-500;
    }
    .notification-icon {
        @apply flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600;
    }
    
    /* Custom pagination styles to match the design */
    .pagination {
        @apply flex justify-between items-center px-4 py-3 sm:px-6;
    }
    .pagination-info {
        @apply text-sm text-gray-700;
    }
    .pagination-links {
        @apply flex-1 flex justify-between sm:justify-end;
    }
    .pagination-links a {
        @apply relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50;
    }
    .pagination-links .page-link {
        @apply mx-1;
    }
    .pagination-links .page-item.active .page-link {
        @apply bg-blue-50 border-blue-500 text-blue-600;
    }
    .pagination-links .page-item.disabled .page-link {
        @apply bg-gray-100 text-gray-400 cursor-not-allowed;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 mr-3">
                <i class="fas fa-bell"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Notificaciones</h1>
        </div>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
        <a href="{{ route('student.notifications.mark-all-read') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mt-4 md:mt-0">
            <i class="fas fa-check-double mr-2"></i> Marcar todas como leídas
        </a>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($notifications->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-bell-slash text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg">No tienes notificaciones</p>
            </div>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                <li class="notification-item {{ $notification->read_at ? '' : 'notification-unread' }}">
                    <a href="{{ route('student.notifications.show', $notification) }}" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-start">
                                <!-- Notification Icon -->
                                <div class="notification-icon mr-3">
                                    @if(str_contains($notification->type, 'Enrollment'))
                                        <i class="fas fa-user-graduate"></i>
                                    @elseif(str_contains($notification->type, 'Content'))
                                        <i class="fas fa-file-alt"></i>
                                    @elseif(str_contains($notification->type, 'Completed'))
                                        <i class="fas fa-trophy"></i>
                                    @else
                                        <i class="fas fa-info-circle"></i>
                                    @endif
                                </div>
                                
                                <!-- Notification Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $notification->data['title'] ?? 'Nueva notificación' }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $notification->data['message'] ?? 'No hay mensaje disponible' }}
                                    </p>
                                </div>
                                <!-- New Badge -->
                                @if(!$notification->read_at)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Nueva
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
