<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Announcements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Pinned Announcements -->
            @if($pinnedAnnouncements->count() > 0)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">📌 Pinned Announcements</h3>
                    <div class="space-y-4">
                        @foreach($pinnedAnnouncements as $announcement)
                            <a href="{{ route('announcements.show', $announcement) }}" class="block bg-white dark:bg-gray-800 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start gap-2 mb-2">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                        {{ $announcement->category === 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                        {{ $announcement->category === 'academic' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        {{ $announcement->category === 'event' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                        {{ $announcement->category === 'general' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                                        {{ ucfirst($announcement->category) }}
                                    </span>
                                    @if($announcement->course)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $announcement->course->code }}</span>
                                    @endif
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $announcement->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ Str::limit($announcement->content, 100) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $announcement->published_at->diffForHumans() }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- All Announcements -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">All Announcements</h3>
                    
                    @if($announcements->count() > 0)
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                                <a href="{{ route('announcements.show', $announcement) }}" class="block border border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <div class="flex items-start gap-2 mb-2">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                            {{ $announcement->category === 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                            {{ $announcement->category === 'academic' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                            {{ $announcement->category === 'event' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                            {{ $announcement->category === 'general' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                                            {{ ucfirst($announcement->category) }}
                                        </span>
                                        @if($announcement->course)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $announcement->course->code }}</span>
                                        @endif
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $announcement->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ Str::limit($announcement->content, 150) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        Posted by {{ $announcement->creator->name }} • {{ $announcement->published_at->diffForHumans() }}
                                    </p>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $announcements->links() }}
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No announcements available at this time.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

