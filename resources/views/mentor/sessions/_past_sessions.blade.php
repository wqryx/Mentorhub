@if($pastSessions->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estudiante / Curso
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Detalles de la Sesión
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado / Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pastSessions as $session)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                <img class="h-12 w-12 rounded-full" src="{{ $session->mentee->profile_photo_url }}" alt="{{ $session->mentee->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $session->mentee->name }}</div>
                                <div class="text-sm text-gray-500">{{ $session->mentee->email }}</div>
                                @if($session->course)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-book mr-1"></i> {{ $session->course->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-md bg-blue-50 text-blue-600">
                                <i class="far fa-calendar-alt text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($session->start_time)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                    <span class="mx-2">•</span>
                                    {{ $session->duration }} minutos
                                </div>
                                @if($session->description)
                                    <div class="mt-1 text-sm text-gray-600 line-clamp-2">
                                        {{ $session->description }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col items-start space-y-2">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $session->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                ($session->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $session->status)) }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('mentor.sessions.show', $session) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="far fa-eye mr-1"></i> Detalles
                                </a>
                                @if(!$session->reviewed_by_mentor && $session->status === 'completed')
                                    <button @click="openModal('reviewSessionModal', { id: {{ $session->id }} })" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-star mr-1"></i> Valorar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $pastSessions->links() }}
    </div>
@else
    <div class="text-center py-12">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No hay sesiones pasadas</h3>
        <p class="mt-1 text-sm text-gray-500">Todavía no has tenido ninguna sesión de mentoría.</p>
        <div class="mt-6">
            <a href="{{ route('mentor.sessions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus-circle mr-2"></i> Programar nueva sesión
            </a>
        </div>
    </div>
@endif
