@props(['date', 'month', 'title', 'timeRange', 'mentorName' => null, 'mentorAvatar' => null])

<div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all-smooth">
    <div class="flex items-start">
        <div class="flex-shrink-0 mr-4 text-center">
            <div class="bg-blue-100 rounded-t-lg py-1 px-3">
                <span class="text-xs font-medium text-blue-800">{{ $month }}</span>
            </div>
            <div class="bg-white border border-blue-100 rounded-b-lg py-2 px-3">
                <span class="text-xl font-bold text-gray-800">{{ $date }}</span>
            </div>
        </div>
        <div>
            <h4 class="font-medium text-gray-900">{{ $title }}</h4>
            <p class="text-sm text-gray-600 mt-1">{{ $timeRange }}</p>
            
            @if($mentorName)
                <div class="mt-2 flex items-center">
                    @if($mentorAvatar)
                        <img src="{{ $mentorAvatar }}" alt="{{ $mentorName }}" class="h-6 w-6 rounded-full border border-white shadow-sm">
                    @else
                        <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-xs font-medium text-blue-700">{{ substr($mentorName, 0, 1) }}</span>
                        </div>
                    @endif
                    <span class="ml-2 text-xs text-gray-600">Con {{ $mentorName }}</span>
                </div>
            @endif
            
            <div class="mt-3 flex space-x-2">
                <a href="#" class="text-xs bg-white border border-gray-300 rounded px-2 py-1 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-colors">
                    Detalles
                </a>
                <a href="#" class="text-xs bg-blue-500 border border-blue-500 rounded px-2 py-1 text-white hover:bg-blue-600 transition-colors">
                    Unirse
                </a>
            </div>
        </div>
    </div>
</div>
