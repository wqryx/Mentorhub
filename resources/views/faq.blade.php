<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes - MentorHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .faq-item {
            @apply bg-white border border-gray-200 rounded-lg overflow-hidden mb-4;
        }
        .faq-question {
            @apply p-5 cursor-pointer flex justify-between items-center font-medium text-gray-800 hover:bg-gray-50 transition-colors;
        }
        .faq-answer {
            @apply px-5 pb-5 pt-0 text-gray-600 border-t border-gray-100 hidden;
        }
        .faq-item.active .faq-answer {
            @apply block;
        }
        .faq-item.active .faq-question {
            @apply bg-gray-50;
        }
        .faq-icon {
            @apply transition-transform duration-200;
        }
        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <a href="/" class="inline-block mb-6">
                <span class="text-2xl font-bold text-indigo-600">MentorHub</span>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Preguntas Frecuentes</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Encuentra respuestas a las preguntas más comunes sobre nuestros servicios, planes de suscripción y más.
            </p>
        </div>

        <div class="max-w-3xl mx-auto">
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-200">Cuenta y Registro</h2>
                
                <div class="faq-item" x-data="{ open: false }">
                    <div class="faq-question" @click="open = !open">
                        <span>¿Cómo me registro en MentorHub?</span>
                        <svg class="faq-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer" x-show="open" x-transition>
                        <p>Para registrarte en MentorHub, sigue estos pasos:</p>
                        <ol class="list-decimal pl-5 mt-2 space-y-1">
                            <li>Haz clic en "Registrarse" en la esquina superior derecha</li>
                            <li>Selecciona tu rol (Estudiante o Mentor)</li>
                            <li>Completa el formulario con tus datos personales</li>
                            <li>Verifica tu correo electrónico</li>
                            <li>¡Listo! Ya puedes acceder a tu cuenta</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item" x-data="{ open: false }">
                    <div class="faq-question" @click="open = !open">
                        <span>¿Puedo cambiar mi dirección de correo electrónico?</span>
                        <svg class="faq-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer" x-show="open" x-transition>
                        <p>Sí, puedes cambiar tu dirección de correo electrónico en cualquier momento desde la configuración de tu cuenta. Ten en cuenta que necesitarás verificar la nueva dirección de correo electrónico antes de que entre en efecto.</p>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-200">Sesiones de Mentoría</h2>
                
                <div class="faq-item" x-data="{ open: false }">
                    <div class="faq-question" @click="open = !open">
                        <span>¿Cómo programo una sesión con un mentor?</span>
                        <svg class="faq-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer" x-show="open" x-transition>
                        <p>Para programar una sesión:</p>
                        <ol class="list-decimal pl-5 mt-2 space-y-1">
                            <li>Inicia sesión en tu cuenta</li>
                            <li>Navega hasta la página del mentor con el que deseas agendar</li>
                            <li>Haz clic en "Agendar sesión"</li>
                            <li>Selecciona una fecha y hora disponibles</li>
                            <li>Confirma los detalles de la sesión</li>
                            <li>Recibirás un correo de confirmación con los detalles de la conexión</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item" x-data="{ open: false }">
                    <div class="faq-question" @click="open = !open">
                        <span>¿Puedo cancelar o reprogramar una sesión?</span>
                        <svg class="faq-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer" x-show="open" x-transition>
                        <p>Sí, puedes cancelar o reprogramar una sesión hasta 24 horas antes de la hora programada sin ningún cargo. Para hacerlo:</p>
                        <ol class="list-decimal pl-5 mt-2 space-y-1">
                            <li>Ve a "Mis sesiones" en tu panel de control</li>
                            <li>Encuentra la sesión que deseas modificar</li>
                            <li>Haz clic en "Cancelar" o "Reprogramar"</li>
                            <li>Sigue las instrucciones en pantalla</li>
                        </ol>
                        <p class="mt-2 text-sm text-gray-500">* Las cancelaciones con menos de 24 horas de anticipación pueden estar sujetas a cargos según la política del mentor.</p>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-200">Pagos y Facturación</h2>
                
                <div class="faq-item" x-data="{ open: false }">
                    <div class="faq-question" @click="open = !open">
                        <span>¿Qué métodos de pago aceptan?</span>
                        <svg class="faq-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer" x-show="open" x-transition>
                        <p>Aceptamos los siguientes métodos de pago:</p>
                        <ul class="list-disc pl-5 mt-2 space-y-1">
                            <li>Tarjetas de crédito y débito (Visa, MasterCard, American Express)</li>
                            <li>PayPal</li>
                            <li>Transferencia bancaria (solo para planes empresariales)</li>
                        </ul>
                        <p class="mt-2 text-sm text-gray-500">Todas las transacciones están protegidas con cifrado SSL de 256 bits para garantizar la máxima seguridad.</p>
                    </div>
                </div>

                <div class="faq-item" x-data="{ open: false }">
                    <div class="faq-question" @click="open = !open">
                        <span>¿Ofrecen reembolsos?</span>
                        <svg class="faq-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="faq-answer" x-show="open" x-transition>
                        <p>Sí, ofrecemos reembolsos bajo las siguientes condiciones:</p>
                        <ul class="list-disc pl-5 mt-2 space-y-1">
                            <li>Para sesiones individuales: Reembolso completo si se cancela con 24 horas de anticipación</li>
                            <li>Para paquetes de sesiones: Reembolso prorrateado por las sesiones no utilizadas</li>
                            <li>Para suscripciones mensuales: Se puede cancelar en cualquier momento, sin cargos adicionales</li>
                        </ul>
                        <p class="mt-2">Para solicitar un reembolso, por favor contáctanos a través de nuestro <a href="/contacto" class="text-indigo-600 hover:underline">formulario de contacto</a>.</p>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 rounded-xl p-6 text-center">
                <h3 class="text-lg font-medium text-indigo-800 mb-2">¿No encontraste lo que buscabas?</h3>
                <p class="text-indigo-700 mb-4">Estamos aquí para ayudarte. Contáctanos y te responderemos lo antes posible.</p>
                <a href="/contacto" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Contáctanos
                </a>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-gray-100 text-center">
            <a href="/" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver al inicio
            </a>
        </div>
    </div>

    <!-- Alpine.js para la funcionalidad de acordeón -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <script>
        // Script para asegurar compatibilidad con navegadores antiguos
        document.addEventListener('alpine:init', () => {
            // Inicialización de Alpine.js
        });
    </script>
</body>
</html>
