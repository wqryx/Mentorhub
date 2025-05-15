@props(['variant' => 'primary', 'size' => 'md'])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-md transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2';

$variantClasses = match($variant) {
    'primary' => 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500',
    'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-blue-500',
    'success' => 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-500',
    'danger' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
    'white' => 'bg-white text-gray-800 border border-gray-200 hover:text-blue-500 hover:border-blue-500 focus:ring-blue-500',
    default => 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500',
};

$sizeClasses = match($size) {
    'xs' => 'text-xs px-2 py-1',
    'sm' => 'text-sm px-3 py-1.5',
    'md' => 'text-sm px-4 py-2',
    'lg' => 'text-base px-5 py-2.5',
    'xl' => 'text-lg px-6 py-3',
    default => 'text-sm px-4 py-2',
};

$classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses;
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
