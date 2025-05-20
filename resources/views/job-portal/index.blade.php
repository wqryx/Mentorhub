@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Portal de Empleo</h1>
        
        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-4">¿Qué ofrecemos?</h2>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-600 mr-2"></i>
                        <span>Acceso a ofertas de empleo relevantes</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-600 mr-2"></i>
                        <span>Recursos para desarrollo profesional</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-600 mr-2"></i>
                        <span>Orientación para buscar empleo</span>
                    </li>
                </ul>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-4">Cómo usar el portal</h2>
                <p class="text-gray-600">
                    Para acceder a las ofertas de empleo, inicia sesión en tu cuenta y navega por las diferentes categorías disponibles.
                    Puedes filtrar las ofertas según tus preferencias y aplicar a las que consideres más adecuadas para tu perfil.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
