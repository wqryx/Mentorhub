@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Política de Privacidad</h1>
        
        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-4">Información que Recopilamos</h2>
                <p class="text-gray-600">
                    Recopilamos información personal que nos proporcionas voluntariamente, como tu nombre, correo electrónico y datos de perfil.
                </p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-4">Uso de la Información</h2>
                <p class="text-gray-600">
                    Utilizamos tu información para:
                    <ul class="list-disc list-inside mt-2">
                        <li>Proporcionar y mantener nuestros servicios</li>
                        <li>Mejorar la experiencia del usuario</li>
                        <li>Comunicarnos contigo</li>
                    </ul>
                </p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="font-semibold mb-4">Seguridad de la Información</h2>
                <p class="text-gray-600">
                    Implementamos medidas de seguridad para proteger tu información personal contra accesos no autorizados.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
