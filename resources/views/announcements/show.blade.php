<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Announcement') }}
            </h2>
            <a href="{{ route('announcements.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                ← Back to Announcements
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Category and Badges -->
                    <div class="flex items-center gap-2 mb-4">
                        @if($announcement->is_pinned)
                            <span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 rounded">📌 Pinned</span>
                        @endif
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                            {{ $announcement->category === 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                            {{ $announcement->category === 'academic' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                            {{ $announcement->category === 'event' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                            {{ $announcement->category === 'general' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                            {{ ucfirst($announcement->category) }}
                        </span>
                        @if($announcement->course)
                            <span class="inline-block px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded">
                                {{ $announcement->course->code }}
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $announcement->title }}</h1>

                    <!-- Meta Info -->
                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <span class="font-medium">Posted by:</span> {{ $announcement->creator->name }}
                        </div>
                        <div>
                            <span class="font-medium">Published:</span> {{ $announcement->published_at->format('F j, Y \a\t g:i A') }}
                        </div>
                        <div>
                            <span class="font-medium">Audience:</span> {{ ucfirst($announcement->audience) }}
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $announcement->content }}</p>
                    </div>

                    <!-- Back Button -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('announcements.index') }}" class="inline-block bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-6 py-2 rounded-md">
                            ← Back to All Announcements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

