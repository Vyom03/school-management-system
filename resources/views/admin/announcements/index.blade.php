<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Announcements') }}
            </h2>
            <a href="{{ route('admin.announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                + New Announcement
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($announcements->count() > 0)
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 {{ $announcement->is_pinned ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
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
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Audience: {{ ucfirst($announcement->audience) }}</span>
                                                @if($announcement->course)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">• {{ $announcement->course->code }}</span>
                                                @endif
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $announcement->title }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ Str::limit($announcement->content, 150) }}</p>
                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                By {{ $announcement->creator->name }} • 
                                                @if($announcement->published_at)
                                                    Published {{ $announcement->published_at->diffForHumans() }}
                                                @else
                                                    <span class="text-orange-600 dark:text-orange-400">Draft</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm">Edit</a>
                                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('Delete this announcement?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $announcements->links() }}
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No announcements yet. <a href="{{ route('admin.announcements.create') }}" class="text-blue-600 hover:underline">Create one</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

