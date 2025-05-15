@props(['title', 'value', 'icon', 'change' => null, 'changeType' => 'neutral', 'link' => null])

<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6 overflow-hidden relative">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $value }}</p>
            
            @if($change)
                <div class="mt-1 flex items-center text-sm">
                    @if($changeType === 'increase')
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        <span class="text-green-500">{{ $change }}</span>
                    @elseif($changeType === 'decrease')
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                        <span class="text-red-500">{{ $change }}</span>
                    @else
                        <span class="text-gray-500">{{ $change }}</span>
                    @endif
                </div>
            @endif
        </div>
        
        <div class="p-3 bg-blue-50 rounded-lg">
            {{ $icon }}
        </div>
    </div>
    
    @if($link)
        <a href="{{ $link }}" class="mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center">
            Ver más
            <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    @endif
</div>
