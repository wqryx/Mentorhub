<!-- Hero Section -->
<section class="hero-gradient text-white py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    Aprende, enseña y conecta con expertos
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">
                    Bienvenido a tu comunidad de aprendizaje.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('register') }}" class="btn-primary bg-white text-primary-color hover:bg-gray-100">
                        Únete ahora
                    </a>
                    <a href="/tutoriales" class="text-white border-2 border-white px-6 py-3 rounded-md font-medium hover:bg-white hover:bg-opacity-10 transition-all">
                        Explorar tutoriales gratuitos
                    </a>
                </div>
            </div>
            <div class="md:w-1/2">
                <img src="https://cdn.pixabay.com/photo/2018/01/17/07/06/laptop-3087585_960_720.jpg" alt="Comunidad de aprendizaje" class="rounded-lg shadow-xl w-full h-auto object-cover">
            </div>
        </div>
    </div>
</section>

<!-- Sección de Funcionalidades -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Lo que ofrecemos</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                MentorHub te ofrece todo lo que necesitas para impulsar tu aprendizaje y desarrollo profesional.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Tutoriales y Cursos -->
            <div class="feature-card bg-white rounded-lg p-6 shadow hover:shadow-lg">
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-primary-color" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Tutoriales y cursos</h3>
                <p class="text-gray-600">
                    Accede a una amplia biblioteca de tutoriales y cursos en diferentes áreas y niveles de dificultad.
                </p>
            </div>
            
            <!-- Mentorías -->
            <div class="feature-card bg-white rounded-lg p-6 shadow hover:shadow-lg">
                <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Sesiones de mentoría</h3>
                <p class="text-gray-600">
                    Conecta con mentores expertos para sesiones 1 a 1 o grupales y acelera tu aprendizaje.
                </p>
            </div>
            
            <!-- Foros -->
            <div class="feature-card bg-white rounded-lg p-6 shadow hover:shadow-lg">
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Foros de discusión</h3>
                <p class="text-gray-600">
                    Participa en foros temáticos donde puedes resolver dudas y compartir conocimientos con la comunidad.
                </p>
            </div>
            
            <!-- Comunidad -->
            <div class="feature-card bg-white rounded-lg p-6 shadow hover:shadow-lg">
                <div class="w-14 h-14 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Comunidad activa</h3>
                <p class="text-gray-600">
                    Únete a una comunidad vibrante con chat en tiempo real y eventos virtuales regulares.
                </p>
            </div>
        </div>
    </div>
</section>
