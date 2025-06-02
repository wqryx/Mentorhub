@extends('admin.layouts.app')

@section('title', $title ?? 'Configuración')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                {{ $title ?? 'Configuración' }}
            </h2>
            @isset($description)
            <p class="mt-1 text-sm text-gray-500">
                {{ $description }}
            </p>
            @endisset
        </div>
        
        @if(isset($actions))
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                {{ $actions }}
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="border-t border-gray-200">
            <div class="flex flex-col md:flex-row">
                <!-- Sidebar -->
                <div class="w-full md:w-64 bg-gray-50 border-r border-gray-200">
                    <nav class="mt-2">
                        <a href="{{ route('admin.settings.general') }}" class="group flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.settings.general') ? 'bg-blue-50 border-r-4 border-blue-600 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-cog mr-3 text-gray-500 group-hover:text-gray-700"></i>
                            General
                        </a>
                        <a href="{{ route('admin.settings.notifications') }}" class="group flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.settings.notifications') ? 'bg-blue-50 border-r-4 border-blue-600 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-bell mr-3 text-gray-500 group-hover:text-gray-700"></i>
                            Notificaciones
                        </a>
                        <a href="{{ route('admin.settings.appearance') }}" class="group flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.settings.appearance') ? 'bg-blue-50 border-r-4 border-blue-600 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-paint-brush mr-3 text-gray-500 group-hover:text-gray-700"></i>
                            Apariencia
                        </a>
                    </nav>
                </div>

                <!-- Main content -->
                <div class="flex-1 p-6">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
