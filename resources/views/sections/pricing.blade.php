<!-- Pricing Section -->
<section id="pricing" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Planes flexibles para todos</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Elige el plan que mejor se adapte a tus necesidades y objetivos profesionales.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Basic Plan -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:-translate-y-1">
                <div class="p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Plan Básico</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-gray-900">€29</span>
                        <span class="text-gray-500 ml-1">/mes</span>
                    </div>
                    <p class="text-gray-500 mb-6">Perfecto para quienes están comenzando su carrera profesional.</p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">2 sesiones mensuales</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">Acceso a la comunidad</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">Recursos básicos</span>
                        </li>
                    </ul>
                    
                    <a href="{{ route('register') }}" class="block text-center py-3 px-6 border border-blue-500 rounded-md text-blue-500 font-medium hover:bg-blue-50 transition-colors">
                        Comenzar ahora
                    </a>
                </div>
            </div>
            
            <!-- Pro Plan (Featured) -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transform scale-105 z-10 border-t-4 border-blue-500">
                <div class="bg-blue-500 py-1 px-4">
                    <p class="text-xs font-bold text-white text-center uppercase tracking-wide">Más popular</p>
                </div>
                <div class="p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Plan Profesional</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-gray-900">€79</span>
                        <span class="text-gray-500 ml-1">/mes</span>
                    </div>
                    <p class="text-gray-500 mb-6">Ideal para profesionales buscando impulsar su carrera.</p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">5 sesiones mensuales</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">Acceso prioritario a mentores</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">Recursos avanzados</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">Revisión de CV y portafolio</span>
                        </li>
                    </ul>
                    
                    <a href="{{ route('register') }}" class="block text-center py-3 px-6 bg-blue-500 rounded-md text-white font-medium hover:bg-blue-600 transition-colors">
                        Elegir este plan
                    </a>
                </div>
            </div>
            
            <!-- Enterprise Plan -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:-translate-y-1">
                <div class="p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Plan Empresarial</h3>
                    <div class="flex items-baseline mb-4">
                        <span class="text-4xl font-extrabold text-gray-900">€199</span>
                        <span class="text-gray-500 ml-1">/mes</span>
                    </div>
                    <p class="text-gray-500 mb-6">Para equipos y profesionales de alto nivel.</p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">10 sesiones mensuales</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">Mentores exclusivos</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">Recursos premium</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">Sesiones de grupo</span>
                        </li>
                    </ul>
                    
                    <a href="{{ route('register') }}" class="block text-center py-3 px-6 border border-blue-500 rounded-md text-blue-500 font-medium hover:bg-blue-50 transition-colors">
                        Contactar ventas
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-12 text-center">
            <p class="text-gray-500">¿Necesitas un plan personalizado para tu empresa? <a href="#" class="text-blue-600 font-medium hover:text-blue-800">Contáctanos</a></p>
        </div>
    </div>
</section>
