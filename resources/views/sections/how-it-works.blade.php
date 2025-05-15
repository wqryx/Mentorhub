<!-- How It Works Section -->
<section id="how-it-works" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Cómo funciona</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Conectarte con mentores y aprender de ellos nunca ha sido tan fácil. Sigue estos simples pasos:</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="bg-white p-8 rounded-lg shadow-md relative">
                <div class="absolute -top-4 -left-4 bg-blue-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold">1</div>
                <h3 class="text-xl font-semibold mb-4 pt-2">Crea tu perfil</h3>
                <p class="text-gray-600 mb-4">Regístrate y completa tu perfil indicando tus intereses, objetivos y las áreas en las que buscas crecer profesionalmente.</p>
                <svg class="h-12 w-12 text-blue-500 mx-auto mt-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            
            <!-- Step 2 -->
            <div class="bg-white p-8 rounded-lg shadow-md relative">
                <div class="absolute -top-4 -left-4 bg-blue-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold">2</div>
                <h3 class="text-xl font-semibold mb-4 pt-2">Encuentra tu mentor</h3>
                <p class="text-gray-600 mb-4">Explora nuestro directorio de mentores, filtra por especialidad, experiencia o calificaciones y encuentra el mentor perfecto para ti.</p>
                <svg class="h-12 w-12 text-blue-500 mx-auto mt-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <!-- Step 3 -->
            <div class="bg-white p-8 rounded-lg shadow-md relative">
                <div class="absolute -top-4 -left-4 bg-blue-500 text-white w-10 h-10 rounded-full flex items-center justify-center text-xl font-bold">3</div>
                <h3 class="text-xl font-semibold mb-4 pt-2">Agenda y conecta</h3>
                <p class="text-gray-600 mb-4">Programa una sesión en el horario que mejor te convenga, realiza el pago y conecta por videollamada para tu sesión personalizada.</p>
                <svg class="h-12 w-12 text-blue-500 mx-auto mt-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        
        <div class="mt-16 flex justify-center">
            <a href="{{ route('register') }}" class="btn-primary bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg shadow-md transition-colors">
                Comienza tu viaje hoy
            </a>
        </div>
    </div>
</section>
