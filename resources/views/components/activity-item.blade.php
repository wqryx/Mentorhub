@props(['type', 'description', 'time', 'user' => null, 'userAvatar' => null])

<div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-all-smooth">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            @if($type === 'session')
                <div class="bg-blue-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @elseif($type === 'message')
                <div class="bg-green-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
            @elseif($type === 'review')
                <div class="bg-yellow-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
            @else
                <div class="bg-gray-100 p-2 rounded-full">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            @endif
        </div>
        <div class="ml-4 flex-1">
            <p class="text-sm text-gray-800">{{ $description }}</p>
            <div class="mt-1 flex justify-between items-center">
                <p class="text-xs text-gray-500">{{ $time }}</p>
                
                @if($user)
                <div class="flex items-center">
                    @if($userAvatar)
                        <img src="{{ $userAvatar }}" alt="{{ $user }}" class="w-5 h-5 rounded-full mr-1 border border-gray-200">
                    @endif
                    <span class="text-xs text-gray-600">{{ $user }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
