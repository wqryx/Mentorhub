@extends('mentor.layouts.app')

@section('title', 'Estudiantes del Curso: ' . ($course->name ?? 'N/A') . ' - MentorHub')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Estudiantes Inscritos en: <span class="text-blue-600">{{ $course->name ?? 'Curso Desconocido' }}</span></h1>
            @if(isset($course))
            <p class="text-sm text-gray-500">Código: {{ $course->code }}</p>
            @endif
        </div>
        <div>
            @if(isset($course))
            <a href="{{ route('mentor.courses.show', $course->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mb-2 md:mb-0 md:mr-2">
                <i class="fas fa-info-circle mr-2"></i> Ver Detalles del Curso
            </a>
            @endif
            <a href="{{ route('mentor.courses.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Mis Cursos
            </a>
        </div>

    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscripción</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progreso</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Actividad</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ $student->profile_image ? asset('storage/' . $student->profile_image) : asset('images/default-avatar.png') }}" alt="{{ $student->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                            @if($student->pivot->is_favorite)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-star mr-1"></i> Destacado
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->pivot->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full {{ $student->pivot->progress >= 100 ? 'bg-green-600' : 'bg-blue-600' }}" style="width: {{ $student->pivot->progress }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $student->pivot->progress }}% completado</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->pivot->last_activity ? $student->pivot->last_activity->diffForHumans() : 'Nunca' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->pivot->completed_at)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Completado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En progreso
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                                        <div>
                                            <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500" id="menu-button" aria-expanded="true" aria-haspopup="true">
                                                Acciones
                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                            <div class="py-1" role="none">
                                                <button type="button" @click="open = false; showProgressModal('{{ $student->id }}', '{{ addslashes($student->name) }}', '{{ $student->pivot->progress }}')" class="w-full text-left text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                    <i class="fas fa-chart-line mr-2 text-green-500"></i> Ver progreso
                                                </button>
                                                <button type="button" @click="open = false; showScheduleModal('{{ $student->id }}', '{{ addslashes($student->name) }}')" class="w-full text-left text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                    <i class="fas fa-calendar-plus mr-2 text-purple-500"></i> Programar sesión
                                                </button>
                                                <div class="border-t border-gray-100 my-1"></div>
                                                @if($student->pivot->is_favorite)
                                                    <form action="{{ route('mentor.courses.students.unfavorite', [$course->id, $student->id]) }}" method="POST" class="inline w-full">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                            <i class="fas fa-star-half-alt mr-2"></i> Quitar de destacados
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('mentor.courses.students.favorite', [$course->id, $student->id]) }}" method="POST" class="inline w-full">
                                                        @csrf
                                                        <button type="submit" class="w-full text-left text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                            <i class="far fa-star mr-2 text-yellow-500"></i> Marcar como destacado
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                        <div class="px-6 py-4 bg-white border-t border-gray-200">
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay estudiantes inscritos</h3>
                            <p class="mt-1 text-sm text-gray-500">Aún no hay estudiantes inscritos en este curso.</p>
                            <div class="mt-6">
                                <a href="{{ route('mentor.courses.show', $course->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>
                                    Volver al curso
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<!-- Progress Modal -->
<div x-data="{
    show: false,
    student: { id: '', name: '', progress: 0 },
    loading: true,
    error: null,
    activity: {
        week: '0h 0m',
        total: '0h 0m',
        recent: []
    },
    init() {
        this.$watch('show', value => {
            if (value) {
                document.body.classList.add('overflow-hidden');
                this.fetchActivity();
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        });
        
        // Listen for open-modal event
        this.$el.addEventListener('open-modal', (e) => {
            if (e.detail === 'progressModal') {
                this.show = true;
            }
        });
        
        // Listen for set-student event
        this.$el.addEventListener('set-student', (e) => {
            this.student = { ...this.student, ...e.detail };
        });
    },
    async fetchActivity() {
        this.loading = true;
        this.error = null;
        
        try {
            // Simulated API call - replace with actual API endpoint
            // const response = await fetch(`/api/students/${this.student.id}/activity`);
            // const data = await response.json();
            
            // Simulated data - remove in production
            await new Promise(resolve => setTimeout(resolve, 500));
            const data = {
                week: '5h 30m',
                total: '42h 15m',
                recent: [
                    { date: 'Hoy', activity: 'Completó el módulo 3', duration: '1h 15m', status: 'completed' },
                    { date: 'Ayer', activity: 'Vio el video: Introducción a PHP', duration: '45m', status: 'in_progress' },
                    { date: 'Hace 2 días', activity: 'Completó la tarea: Variables y tipos de datos', duration: '30m', status: 'completed' },
                    { date: 'Hace 3 días', activity: 'Inició el curso', duration: '2h 0m', status: 'completed' }
                ]
            };
            
            this.activity = data;
        } catch (error) {
            console.error('Error fetching activity:', error);
            this.error = 'No se pudo cargar la actividad del estudiante';
        } finally {
            this.loading = false;
        }
    },
    async sendProgressReport() {
        try {
            // Simulated API call - replace with actual API endpoint
            // await fetch(`/api/students/${this.student.id}/progress-report`, { method: 'POST' });
            
            // Show success message
            this.$dispatch('notify', {
                type: 'success',
                message: 'Reporte de progreso enviado correctamente'
            });
            
            // Close modal after a short delay
            setTimeout(() => {
                this.show = false;
            }, 1500);
        } catch (error) {
            console.error('Error sending progress report:', error);
            this.$dispatch('notify', {
                type: 'error',
                message: 'Error al enviar el reporte de progreso'
            });
        }
    },
    getStatusBadgeClass(status) {
        const classes = {
            'completed': 'bg-green-100 text-green-800',
            'in_progress': 'bg-yellow-100 text-yellow-800',
            'not_started': 'bg-gray-100 text-gray-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getStatusText(status) {
        const statusMap = {
            'completed': 'Completado',
            'in_progress': 'En progreso',
            'not_started': 'No iniciado'
        };
        return statusMap[status] || status;
    }
}" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" x-ref="dialog" aria-modal="true" style="display: none;">
    <div x-show="show" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         @click="show = false"
         aria-hidden="true">
    </div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:p-6">
                <div>
                    <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                        <button type="button" @click="show = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Progreso de <span x-text="student.name"></span>
                            </h3>
                            <div class="mt-4">
                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <!-- Progress Card -->
                                    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                                        <div class="text-center">
                                            <dt class="text-sm font-medium text-gray-500">Progreso General</dt>
                                            <dd class="mt-4">
                                                <div class="relative">
                                                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                                                        <div class="h-full bg-blue-600 rounded-full transition-all duration-500 ease-in-out" 
                                                             :style="`width: ${student.progress}%`"></div>
                                                    </div>
                                                    <div class="mt-2 text-2xl font-semibold text-gray-900" x-text="`${student.progress}%`"></div>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                    
                                    <!-- Study Time Card -->
                                    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                                        <div class="text-center">
                                            <dt class="text-sm font-medium text-gray-500">Tiempo de Estudio</dt>
                                            <dd class="mt-4 space-y-2">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500">Esta semana:</span>
                                                    <span class="text-sm font-medium text-gray-900" x-text="activity.week"></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500">Total:</span>
                                                    <span class="text-sm font-medium text-gray-900" x-text="activity.total"></span>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Recent Activity -->
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Actividad Reciente</h4>
                                    
                                    <div x-show="loading" class="flex justify-center py-4">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                    </div>
                                    
                                    <div x-show="!loading && activity.recent.length === 0" class="text-center py-4 text-sm text-gray-500">
                                        No hay actividad reciente para mostrar.
                                    </div>
                                    
                                    <div x-show="!loading && activity.recent.length > 0" class="flow-root">
                                        <ul role="list" class="-mb-8">
                                            <template x-for="(item, index) in activity.recent" :key="index">
                                                <li>
                                                    <div class="relative pb-8">
                                                        <span x-show="index !== activity.recent.length - 1" class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                        <div class="relative flex space-x-3">
                                                            <div>
                                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                                    <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                                <div>
                                                                    <p class="text-sm text-gray-700" x-text="item.activity"></p>
                                                                    <p class="text-xs text-gray-500" x-text="item.date"></p>
                                                                </div>
                                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" 
                                                                          :class="getStatusBadgeClass(item.status)" 
                                                                          x-text="getStatusText(item.status)">
                                                                    </span>
                                                                    <div class="mt-1 text-xs text-gray-400" x-text="item.duration"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="sendProgressReport"
                            class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.896 28.896 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z" />
                        </svg>
                        Enviar reporte
                    </button>
                    <button type="button"
                            @click="show = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Session Modal -->
<div x-data="{
    show: false,
    loading: false,
    student: {
        id: null,
        name: ''
    },
    form: {
        student_id: null,
        course_id: '{{ $course->id }}',
        scheduled_at: '',
        duration: 60,
        meeting_link: '',
        notes: ''
    },
    errors: {},
    init() {
        // Listen for the event to open this modal
        window.addEventListener('open-schedule-modal', (e) => {
            this.student = e.detail;
            this.form.student_id = this.student.id;
            this.show = true;
            
            // Initialize flatpickr after the modal is shown
            this.$nextTick(() => {
                flatpickr('#scheduled_at', {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    minDate: 'today',
                    time_24hr: true,
                    minuteIncrement: 15,
                    locale: 'es',
                    defaultHour: new Date().getHours() + 1,
                    defaultMinute: 0
                });
            });
        });
    },
    close() {
        this.show = false;
        this.resetForm();
    },
    resetForm() {
        this.form = {
            student_id: this.student.id,
            course_id: '{{ $course->id }}',
            scheduled_at: '',
            duration: 60,
            meeting_link: '',
            notes: ''
        };
        this.errors = {};
    },
    async submitForm() {
        this.loading = true;
        this.errors = {};
        
        try {
            const response = await fetch('{{ route('mentor.sessions.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(this.form)
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                if (response.status === 422) {
                    this.errors = data.errors || {};
                    throw new Error('Por favor, corrige los errores en el formulario.');
                }
                throw new Error(data.message || 'Error al programar la sesión');
            }
            
            // Show success message
            showNotification('success', 'Sesión programada correctamente');
            
            // Close modal after a short delay
            setTimeout(() => {
                this.close();
                // Optionally refresh the page or update the UI
                // window.location.reload();
            }, 1500);
            
        } catch (error) {
            showNotification('error', error.message || 'Error al programar la sesión');
        } finally {
            this.loading = false;
        }
    }
}" x-show="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" x-ref="dialog" aria-modal="true" style="display: none;">
    <div x-show="show" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         @click="close()"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                
                <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                    <button type="button" @click="close()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            Programar sesión con <span x-text="student.name"></span>
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Fecha y hora</label>
                                <div class="mt-1">
                                    <input type="text" 
                                           id="scheduled_at" 
                                           x-model="form.scheduled_at"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           placeholder="Selecciona fecha y hora"
                                           autocomplete="off"
                                           required>
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.scheduled_at ? errors.scheduled_at[0] : ''"></p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700">Duración (minutos)</label>
                                <div class="mt-1">
                                    <input type="number" 
                                           id="duration" 
                                           x-model="form.duration"
                                           min="15" 
                                           max="240" 
                                           step="15"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           required>
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.duration ? errors.duration[0] : ''"></p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="meeting_link" class="block text-sm font-medium text-gray-700">Enlace de la reunión</label>
                                <div class="mt-1">
                                    <input type="url" 
                                           id="meeting_link" 
                                           x-model="form.meeting_link"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           placeholder="https://meet.google.com/..."
                                           required>
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.meeting_link ? errors.meeting_link[0] : ''"></p>
                                </div>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notas adicionales</label>
                                <div class="mt-1">
                                    <textarea id="notes" 
                                              x-model="form.notes"
                                              rows="3" 
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                              placeholder="Objetivos de la sesión, temas a tratar, etc."></textarea>
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.notes ? errors.notes[0] : ''"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="submitForm()"
                            :disabled="loading"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-75 disabled:cursor-not-allowed">
                        <span x-show="!loading">Programar sesión</span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Procesando...
                        </span>
                    </button>
                    <button type="button" 
                            @click="close()"
                            :disabled="loading"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-75 disabled:cursor-not-allowed">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Update JavaScript to use Alpine.js consistently -->
<script>
    // Initialize tooltips with Tippy.js
    document.addEventListener('alpine:initialized', () => {
        tippy('[data-tippy-content]', {
            placement: 'top',
            animation: 'scale',
            arrow: true,
            theme: 'light',
        });
    });

    // Function to show notifications
    function showNotification(type, message) {
        const event = new CustomEvent('notify', {
            detail: { type, message }
        });
        window.dispatchEvent(event);
    }

    // Function to show progress modal
    function showProgressModal(studentId, studentName, progress) {
        // Trigger event to open the modal
        const event = new CustomEvent('open-modal', { detail: 'progressModal' });
        document.dispatchEvent(event);

        // Set student data
        const studentEvent = new CustomEvent('set-student', {
            detail: {
                id: studentId,
                name: studentName,
                progress: progress
            }
        });
        document.dispatchEvent(studentEvent);
    }

    // Function to show message modal
    function showMessageModal(studentId, studentName) {
        // Trigger event to open the message modal
        const event = new CustomEvent('open-modal', { detail: 'messageModal' });
        document.dispatchEvent(event);

        // Set student data
        const studentEvent = new CustomEvent('set-student', {
            detail: {
                id: studentId,
                name: studentName
            }
        });
        document.dispatchEvent(studentEvent);
    }
    
    // Function to show schedule modal
    function showScheduleModal(studentId, studentName) {
        // Trigger event to open the schedule modal
        const event = new CustomEvent('open-schedule-modal', { 
            detail: {
                id: studentId,
                name: studentName
            }
        });
        window.dispatchEvent(event);
    }
</script>

<!-- Alpine.js Components -->
<script>
    // Notifications Component
    document.addEventListener('alpine:init', () => {
        Alpine.data('notifications', () => ({
            notifications: [],
            init() {
                // Listen for notification events
                window.addEventListener('notify', (e) => {
                    this.addNotification(e.detail.type, e.detail.message);
                });
                
                // Handle close button clicks
                this.$watch('notifications', () => {
                    this.$nextTick(() => {
                        document.querySelectorAll('[x-on\:click="removeNotification(index)"]').forEach(button => {
                            button.addEventListener('click', (e) => {
                                const index = parseInt(e.target.closest('[x-data]')._x_dataStack[0].index);
                                this.removeNotification(index);
                            });
                        });
                    });
                });
            },
            addNotification(type, message) {
                const id = Date.now();
                this.notifications.push({ id, type, message });
                
                // Remove notification after 5 seconds
                setTimeout(() => {
                    this.removeNotification(this.notifications.findIndex(n => n.id === id));
                }, 5000);
            },
            removeNotification(index) {
                if (index >= 0 && index < this.notifications.length) {
                    this.notifications.splice(index, 1);
                }
            },
            getNotificationClass(type) {
                const classes = {
                    success: 'bg-green-50 text-green-800',
                    error: 'bg-red-50 text-red-800',
                    warning: 'bg-yellow-50 text-yellow-800',
                    info: 'bg-blue-50 text-blue-800'
                };
                return classes[type] || 'bg-gray-50 text-gray-800';
            },
            getNotificationIcon(type) {
                const icons = {
                    success: 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z',
                    error: 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.97a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.97a.75.75 0 101.1 1.02L10 11.06l1.97 1.72a.75.75 0 101.02-1.1L11.06 10l1.72-1.97a.75.75 0 10-1.1-1.02L10 8.94 8.03 7.97z',
                    warning: 'M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.653 1.1-.29 2.38-1.516 2.38H3.72c-1.227 0-2.169-1.28-1.516-2.38L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 6a1 1 0 100 2 1 1 0 000-2z',
                    info: 'M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5a.75.75 0 001.5 0V5zM10 12a1 1 0 100 2 1 1 0 000-2z'
                };
                return icons[type] || icons.info;
            }
        }));
    });
</script>

<!-- Notifications Container -->
<div x-data="notifications" class="fixed top-4 right-4 z-50 w-80 space-y-4">
    <template x-for="(notification, index) in notifications" :key="notification.id">
        <div x-show="true" 
             x-transition:enter="transform ease-out duration-300 transition" 
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" 
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" 
             x-transition:leave="transition ease-in duration-100" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="rounded-md p-4 shadow-lg" 
             :class="getNotificationClass(notification.type)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" x-show="notification.type === 'success'" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.97a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.97a.75.75 0 101.1 1.02L10 11.06l1.97 1.72a.75.75 0 101.02-1.1L11.06 10l1.72-1.97a.75.75 0 10-1.1-1.02L10 8.94 8.03 7.97z" clip-rule="evenodd" x-show="notification.type === 'error'" />
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.653 1.1-.29 2.38-1.516 2.38H3.72c-1.227 0-2.169-1.28-1.516-2.38L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" x-show="notification.type === 'warning'" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5a.75.75 0 001.5 0V5zM10 12a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" x-show="notification.type === 'info' || !['success', 'error', 'warning'].includes(notification.type)" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium" x-text="notification.message"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="removeNotification(index)" class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
