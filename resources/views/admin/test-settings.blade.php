@extends('admin.layouts.app')

@section('title', 'Test Settings')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Test Settings</h1>
    
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Current Settings</h2>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
            <p class="mb-2"><strong>Theme:</strong> {{ setting('theme', 'light') }}</p>
            <p class="mb-2"><strong>Site Name:</strong> {{ setting('site_name', config('app.name')) }}</p>
            <p class="mb-2"><strong>Primary Color:</strong> {{ setting('primary_color', '#4F46E5') }}</p>
            <p class="mb-2"><strong>Secondary Color:</strong> {{ setting('secondary_color', '#7C3AED') }}</p>
            <p class="mb-2"><strong>Enable Animations:</strong> {{ setting('enable_animations', true) ? 'Yes' : 'No' }}</p>
            <p class="mb-2"><strong>Header Fixed:</strong> {{ setting('header_fixed', true) ? 'Yes' : 'No' }}</p>
        </div>
    </div>
    
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Test Setting Update</h2>
        <form action="{{ route('admin.settings.test') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Setting Key</label>
                <input type="text" name="key" id="key" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Setting Value</label>
                <input type="text" name="value" id="value" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Setting
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
