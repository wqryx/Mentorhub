@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ $certificate->title }}</h1>
            <a href="{{ route('certificates.index') }}" 
               class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>

        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Información del Certificado</h2>
                <div class="space-y-2">
                    <p><strong>Número de Certificado:</strong> {{ $certificate->certificate_number }}</p>
                    <p><strong>Fecha de Emisión:</strong> {{ $certificate->issue_date->format('d/m/Y') }}</p>
                    <p><strong>Asignatura:</strong> {{ $certificate->module->name }}</p>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Descripción</h2>
                <p class="text-gray-600">{{ $certificate->description }}</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Descargar Certificado</h2>
                <a href="{{ asset('storage/' . $certificate->pdf_path) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-download mr-2"></i> Descargar PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
