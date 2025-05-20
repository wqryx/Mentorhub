@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <a href="{{ route('help.center') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Centro de Ayuda
                </a>
                <h1 class="text-2xl font-bold mt-4">{{ $article->title }}</h1>
                <p class="text-sm text-gray-500">
                    {{ $article->category }} • 
                    {{ $article->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <div class="prose max-w-none">
            {!! $article->content !!}
        </div>

        @if($relatedArticles->count() > 0)
            <div class="mt-8">
                <h2 class="font-semibold mb-4">Artículos Relacionados</h2>
                <div class="space-y-4">
                    @foreach($relatedArticles as $related)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold">
                                <a href="{{ route('help.center.article', $related->slug) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $related->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $related->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
