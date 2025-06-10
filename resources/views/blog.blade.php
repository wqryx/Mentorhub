<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - MentorHub</title>
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
            max-width: 1200px;
            margin: 0 auto;
        }
        .category-tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .article-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .article-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            border-radius: 0.75rem;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4 sm:px-6">
    <div class="container mx-auto">
        <!-- Encabezado -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Blog de Mentoría</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Aprende, inspírate y crece con nuestros artículos sobre mentoría, desarrollo profesional y aprendizaje continuo.
            </p>
        </div>

        <!-- Barra de búsqueda y filtros -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="searchInput"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                        placeholder="Buscar artículos..."
                    >
                </div>
                <div class="flex flex-wrap gap-2">
                    <button 
                        class="category-btn px-4 py-2 rounded-lg font-medium bg-indigo-600 text-white" 
                        data-category="all"
                    >
                        Todos
                    </button>
                    <button 
                        class="category-btn px-4 py-2 rounded-lg font-medium bg-white border border-gray-300 text-gray-700 hover:bg-gray-50" 
                        data-category="mentoria"
                    >
                        Mentoría
                    </button>
                    <button 
                        class="category-btn px-4 py-2 rounded-lg font-medium bg-white border border-gray-300 text-gray-700 hover:bg-gray-50" 
                        data-category="aprendizaje"
                    >
                        Aprendizaje
                    </button>
                    <button 
                        class="category-btn px-4 py-2 rounded-lg font-medium bg-white border border-gray-300 text-gray-700 hover:bg-gray-50" 
                        data-category="liderazgo"
                    >
                        Liderazgo
                    </button>
                </div>
            </div>
        </div>

        <!-- Sección de artículos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12" id="articlesContainer">
            <!-- Los artículos se generarán dinámicamente con JavaScript -->
        </div>

        <!-- Modal para ver artículo completo -->
        <div id="articleModal" class="modal">
            <div class="modal-content">
                <div class="relative">
                    <button class="close-modal absolute top-4 right-4 bg-white rounded-full p-2 shadow-md hover:bg-gray-100">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <img id="modalImage" src="" alt="" class="w-full h-64 object-cover">
                </div>
                <div class="p-6">
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 mb-4"></h2>
                    <div id="modalContent" class="prose max-w-none">
                        <!-- El contenido del artículo se cargará aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let modal, modalTitle, modalImage, modalContent;
        
        // Datos de ejemplo para los artículos
        const articles = [
            {
                id: 1,
                title: 'Cómo ser un mejor mentor en la era digital',
                excerpt: 'Aprende las mejores prácticas para la mentoría en línea y cómo aprovechar las herramientas digitales.',
                category: 'mentoria',
                date: '2025-06-05',
                readTime: '5',
                image: 'https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
                author: {
                    name: 'María González',
                    avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
                    role: 'Mentora Senior'
                },
                content: '<p>La mentoría en la era digital ha evolucionado significativamente en los últimos años. Con el auge del teletrabajo y la educación en línea, los mentores necesitan adaptar sus métodos para seguir siendo efectivos en un entorno virtual.</p><p>Aquí te presentamos algunas estrategias clave para ser un mejor mentor en la era digital:</p><ul><li>Establece canales de comunicación claros</li><li>Utiliza herramientas colaborativas</li><li>Mantén horarios flexibles pero consistentes</li><li>Fomenta la autogestión</li><li>Proporciona retroalimentación constructiva</li></ul>'
            },
            {
                id: 2,
                title: 'Técnicas de aprendizaje acelerado',
                excerpt: 'Descubre métodos probados para aprender más rápido y retener mejor la información.',
                category: 'aprendizaje',
                date: '2025-05-28',
                readTime: '8',
                image: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1073&q=80',
                author: {
                    name: 'Carlos Méndez',
                    avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
                    role: 'Especialista en Aprendizaje'
                },
                content: '<p>El aprendizaje acelerado combina técnicas de estudio efectivas con principios psicológicos para maximizar la retención de información. Este artículo explora las mejores estrategias respaldadas por la ciencia.</p><h3>Técnicas clave:</h3><ol><li>El método Pomodoro</li><li>Repetición espaciada</li><li>Enseñar lo aprendido</li><li>Mapas mentales</li><li>Dormir adecuadamente</li></ol>'
            },
            {
                id: 3,
                title: 'Liderazgo en equipos remotos',
                excerpt: 'Estrategias efectivas para liderar equipos distribuidos y mantener la productividad.',
                category: 'liderazgo',
                date: '2025-05-20',
                readTime: '6',
                image: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
                author: {
                    name: 'Ana Torres',
                    avatar: 'https://randomuser.me/api/portraits/women/68.jpg',
                    role: 'Líder de Equipo'
                },
                content: '<p>El liderazgo en equipos remotos presenta desafíos únicos que requieren un enfoque diferente al del liderazgo tradicional. Este artículo explora estrategias comprobadas para mantener equipos remotos comprometidos y productivos.</p><h3>Estrategias clave:</h3><ul><li>Comunicación clara y frecuente</li><li>Establecer expectativas claras</li><li>Fomentar la autonomía</li><li>Crear oportunidades para la conexión social</li><li>Utilizar las herramientas adecuadas</li></ul>'
            }
        ];
        
        // Inicialización cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a elementos del DOM
            modal = document.getElementById('articleModal');
            modalTitle = document.getElementById('modalTitle');
            modalImage = document.getElementById('modalImage');
            modalContent = document.getElementById('modalContent');
            const searchInput = document.getElementById('searchInput');
            
            // Mostrar artículos al cargar
            displayArticles(articles);
            
            // Event listeners
            if (searchInput) {
                searchInput.addEventListener('input', filterArticles);
            }
            
            // Cerrar modal al hacer clic en el botón de cerrar
            const closeModalBtn = document.querySelector('.close-modal');
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closeModal);
            }
            
            // Cerrar modal al hacer clic fuera del contenido
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
            
            // Inicializar botones de categoría
            initCategoryButtons();

            // Función para inicializar los botones de categoría
            function initCategoryButtons() {
                const categoryButtons = document.querySelectorAll('.category-btn');
                categoryButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Remover clase activa de todos los botones
                        categoryButtons.forEach(btn => {
                            btn.classList.remove('bg-indigo-600', 'text-white');
                            btn.classList.add('bg-white', 'border', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                        });
                        
                        // Añadir clase activa al botón clickeado
                        this.classList.remove('bg-white', 'border', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                        this.classList.add('bg-indigo-600', 'text-white');
                        
                        // Filtrar artículos
                        filterArticles();
                    });
                });
            }
            
            // Función para mostrar artículos en la cuadrícula
            function displayArticles(articlesToShow = articles) {
                const articlesContainer = document.getElementById('articlesContainer');
                if (!articlesContainer) return;
                
                // Asegurarse de que tenemos artículos para mostrar
                if (!articlesToShow || articlesToShow.length === 0) {
                    articlesContainer.innerHTML = `
                        <div class="col-span-3 text-center py-12">
                            <p class="text-gray-500">No se encontraron artículos que coincidan con tu búsqueda.</p>
                        </div>
                    `;
                    return;
                }
                
                // Generar el HTML de las tarjetas de artículos
                articlesContainer.innerHTML = articlesToShow.map(article => `
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden article-card hover:shadow-md transition-shadow duration-300">
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            <img src="${article.image}" 
                                 alt="${article.title}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/600x400?text=Imagen+no+disponible';">
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-3">
                                <span class="category-tag ${getCategoryClass(article.category)} px-3 py-1 text-xs font-medium rounded-full">
                                    ${getCategoryName(article.category)}
                                </span>
                                <span class="text-sm text-gray-500">${formatDate(article.date)}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">${article.title}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">${article.excerpt}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="${article.author.avatar}" 
                                         alt="${article.author.name}" 
                                         class="h-8 w-8 rounded-full mr-2"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text=${article.author.name.charAt(0)}';">
                                    <span class="text-sm font-medium text-gray-700">${article.author.name}</span>
                                </div>
                                <button onclick="showArticle(${article.id})" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center">
                                    Leer más
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
            
            // Función para filtrar artículos
            function filterArticles() {
                const searchInput = document.getElementById('searchInput');
                const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                const activeCategory = document.querySelector('.category-btn.bg-indigo-600')?.dataset.category || 'all';
                
                const filtered = articles.filter(article => {
                    const matchesSearch = article.title.toLowerCase().includes(searchTerm) || 
                                        article.excerpt.toLowerCase().includes(searchTerm) ||
                                        article.content.toLowerCase().includes(searchTerm);
                    const matchesCategory = activeCategory === 'all' || article.category === activeCategory;
                    return matchesSearch && matchesCategory;
                });
                
                displayArticles(filtered);
                
                // Mostrar mensaje si no hay resultados
                const noResults = document.getElementById('no-results');
                if (filtered.length === 0) {
                    if (!noResults) {
                        const message = document.createElement('div');
                        message.id = 'no-results';
                        message.className = 'col-span-1 md:col-span-2 lg:col-span-3 text-center py-12';
                        message.innerHTML = `
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No se encontraron artículos</h3>
                            <p class="mt-1 text-gray-500">Intenta con otros términos de búsqueda o categorías.</p>
                        `;
                        document.getElementById('articlesContainer').parentNode.insertBefore(message, document.getElementById('articlesContainer').nextSibling);
                    } else {
                        noResults.style.display = 'block';
                    }
                } else if (noResults) {
                    noResults.style.display = 'none';
                }
            }
            
            // Función para formatear fechas
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('es-ES', options);
            }
            
            // Función para obtener el nombre de la categoría
            function getCategoryName(category) {
                const categories = {
                    'mentoria': 'Mentoría',
                    'aprendizaje': 'Aprendizaje',
                    'liderazgo': 'Liderazgo'
                };
                return categories[category] || category;
            }
            
            // Función para obtener la clase CSS de la categoría
            function getCategoryClass(category) {
                const classes = {
                    'mentoria': 'bg-blue-100 text-blue-800',
                    'aprendizaje': 'bg-green-100 text-green-800',
                    'liderazgo': 'bg-purple-100 text-purple-800'
                };
                return classes[category] || 'bg-gray-100 text-gray-800';
            }
            
            // Función para cerrar el modal
            function closeModal() {
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            }
            
            // Función para mostrar un artículo en el modal
            window.showArticle = function(articleId) {
                const article = articles.find(a => a.id === articleId);
                if (!article || !modal || !modalTitle || !modalImage || !modalContent) return;
                
                modalTitle.textContent = article.title;
                modalImage.src = article.image;
                modalImage.alt = article.title;
                
                modalContent.innerHTML = `
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <img src="${article.author.avatar}" alt="${article.author.name}" class="h-12 w-12 rounded-full">
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">${article.author.name}</h4>
                                <p class="text-sm text-gray-500">${article.author.role} • ${article.readTime} min de lectura</p>
                            </div>
                        </div>
                        <span class="category-tag ${getCategoryClass(article.category)} px-3 py-1 text-xs font-medium rounded-full">
                            ${getCategoryName(article.category)}
                        </span>
                    </div>
                    <div class="prose max-w-none">
                        ${article.content}
                    </div>
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">¿Te gustó este artículo?</h3>
                        <div class="flex space-x-4">
                            <button class="flex items-center text-gray-500 hover:text-green-600">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                <span>Me gusta</span>
                            </button>
                            <button class="flex items-center text-gray-500 hover:text-blue-600">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <span>Comentar</span>
                            </button>
                            <button class="flex items-center text-gray-500 hover:text-red-600">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span>Compartir</span>
                            </button>
                        </div>
                    </div>
                `;
                
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            };

            // Funcionalidad de los botones de categoría
            const categoryButtons = document.querySelectorAll('.category-btn');
            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remover clase activa de todos los botones
                    categoryButtons.forEach(btn => btn.classList.remove('bg-indigo-600', 'text-white'));
                    // Añadir clase activa al botón clickeado
                    this.classList.add('bg-indigo-600', 'text-white');
                    // Filtrar artículos
                    filterArticles();
                });
            });

            // Event listener para el cierre del modal
            closeModalBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            // Cerrar modal al hacer clic fuera del contenido
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });

            // Función para mostrar un artículo en el modal
            function showArticle(articleId) {
                const article = articles.find(a => a.id === articleId);
                if (article && modalTitle && modalContent) {
                    modalTitle.textContent = article.title;
                    modalContent.innerHTML = `
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="category-tag ${getCategoryClass(article.category)}">
                                    ${getCategoryName(article.category)}
                                </span>
                                <span class="text-sm text-gray-500">${formatDate(article.date)} • ${article.readTime} min de lectura</span>
                            </div>
                            <img src="${article.image}" alt="${article.title}" class="w-full h-64 object-cover rounded-lg mb-6">
                            <div class="prose max-w-none">
                                ${article.content}
                            </div>
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <div class="flex items-center">
                                    <img src="${article.author.avatar}" alt="${article.author.name}" class="h-12 w-12 rounded-full">
                                    <div class="ml-4">
                                        <h4 class="font-medium text-gray-900">${article.author.name}</h4>
                                        <p class="text-sm text-gray-500">${article.author.role}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    modal.style.display = 'flex';
                }
            }

            // Función para formatear fechas
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('es-ES', options);
            }

            // Función para obtener el nombre de la categoría
            function getCategoryName(category) {
                const categories = {
                    'mentoria': 'Mentoría',
                    'aprendizaje': 'Aprendizaje',
                    'liderazgo': 'Liderazgo'
                };
                return categories[category] || category;
            }

            // Función para obtener la clase CSS de la categoría
            function getCategoryClass(category) {
                const classes = {
                    'mentoria': 'bg-blue-100 text-blue-800',
                    'aprendizaje': 'bg-green-100 text-green-800',
                    'liderazgo': 'bg-purple-100 text-purple-800'
                };
                return classes[category] || 'bg-gray-100 text-gray-800';
            }
            
            // Función para mostrar un artículo en el modal
            window.showArticle = function(articleId) {
                const article = articles.find(a => a.id === articleId);
                if (!article || !modal || !modalTitle || !modalImage || !modalContent) return;
                
                modalTitle.textContent = article.title;
                modalImage.src = article.image;
                modalImage.alt = article.title;
                
                modalContent.innerHTML = `
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <img src="${article.author.avatar}" alt="${article.author.name}" class="h-12 w-12 rounded-full">
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">${article.author.name}</h4>
                                <p class="text-sm text-gray-500">${article.author.role} • ${article.readTime} min de lectura</p>
                            </div>
                        </div>
                        <span class="category-tag ${getCategoryClass(article.category)}">
                            ${getCategoryName(article.category)}
                        </span>
                    </div>
                    <div class="prose max-w-none">
                        ${article.content}
                    </div>
                `;
                
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            };
            
            // Función para cerrar el modal
            function closeModal() {
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            }

            // Función para filtrar artículos
            function filterArticles() {
                const articlesContainer = document.getElementById('articlesContainer');
                if (!articlesContainer) return;
                
                const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                const activeCategory = document.querySelector('.category-btn.bg-indigo-600')?.dataset.category || 'all';
                
                const filtered = articles.filter(article => {
                    const matchesSearch = article.title.toLowerCase().includes(searchTerm) || 
                                        article.excerpt.toLowerCase().includes(searchTerm) ||
                                        article.content.toLowerCase().includes(searchTerm);
                    const matchesCategory = activeCategory === 'all' || article.category === activeCategory;
                    return matchesSearch && matchesCategory;
                });
                
                displayArticles(filtered);
                
                // Mostrar mensaje si no hay resultados
                const noResults = document.getElementById('no-results');
                if (filtered.length === 0) {
                    if (!noResults) {
                        const message = document.createElement('div');
                        message.id = 'no-results';
                        message.className = 'col-span-1 md:col-span-2 lg:col-span-3 text-center py-12';
                        message.innerHTML = `
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No se encontraron artículos</h3>
                            <p class="mt-1 text-gray-500">Intenta con otros términos de búsqueda o categorías.</p>
                        `;
                        articlesContainer.parentNode.insertBefore(message, articlesContainer.nextSibling);
                    } else {
                        noResults.style.display = 'block';
                    }
                } else if (noResults) {
                    noResults.style.display = 'none';
                }
            }
            
            // Función para manejar el cambio de categoría
            function handleCategoryChange(category) {
                const buttons = document.querySelectorAll('.category-btn');
                buttons.forEach(btn => {
                    if (btn.dataset.category === category) {
                        btn.classList.remove('bg-white', 'border', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                        btn.classList.add('bg-indigo-600', 'text-white');
                    } else {
                        btn.classList.remove('bg-indigo-600', 'text-white');
                        btn.classList.add('bg-white', 'border', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                    }
                });
                
                filterArticles();
            }
            

        });
    </script>
</body>
</html>
