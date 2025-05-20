@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Centro de Ayuda</h1>

        <div class="space-y-6">
            <!-- Búsqueda -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <form action="{{ route('help.center') }}" method="GET" class="flex space-x-4">
                    <input type="text" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Buscar artículos..." 
                           class="flex-1 px-4 py-2 rounded-lg border-gray-300 shadow-sm">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Categorías -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-4">Categorías</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['General', 'Funcionalidades', 'Soporte', 'Tutorías', 'Certificados'] as $category)
                        <a href="{{ route('help.center') }}?category={{ $category }}" 
                           class="text-center p-3 rounded-lg hover:bg-gray-100">
                            <h3 class="font-medium">{{ $category }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ $articles->where('category', $category)->count() }} artículos
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Lista de artículos -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-4">Artículos</h2>
                <div class="space-y-4">
                    @forelse($articles as $article)
                        <div class="p-4 bg-white rounded-lg shadow-sm">
                            <h3 class="font-semibold">
                                <a href="{{ route('help.center.article', $article->slug) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $article->category }} • 
                                {{ $article->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500">
                            No se encontraron artículos.
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
