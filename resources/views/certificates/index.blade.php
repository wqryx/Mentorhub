@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Mis Certificados</h1>
        
        @if($certificates->isEmpty())
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-600">No tienes certificados emitidos aún.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($certificates as $certificate)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="font-semibold">{{ $certificate->title }}</h2>
                                <p class="text-sm text-gray-500">{{ $certificate->issue_date->format('d/m/Y') }}</p>
                            </div>
                            <a href="{{ route('certificates.show', $certificate) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                Ver Certificado
                            </a>
                        </div>
                        <p class="mt-2 text-gray-600">{{ $certificate->description }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
