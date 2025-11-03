<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Event Details') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.events.edit', $event) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Edit
                </a>
                <a href="{{ route('admin.events.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                    Back to Events
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $event->title }}</h1>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-3 py-1 text-sm font-semibold rounded
                                {{ $event->type === 'academic' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                {{ $event->type === 'holiday' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                {{ $event->type === 'exam' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                {{ $event->type === 'meeting' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                {{ $event->type === 'event' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}">
                                {{ ucfirst($event->type) }}
                            </span>
                            <span class="px-3 py-1 text-sm font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded">
                                {{ ucfirst($event->visibility) }}
                            </span>
                        </div>

                        @if($event->description)
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</h3>
                                <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $event->description }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date & Time</h3>
                                <p class="text-gray-900 dark:text-gray-100">{{ $event->formatted_date_range }}</p>
                                @if($event->is_all_day)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">All Day Event</p>
                                @endif
                            </div>

                            @if($event->course)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course</h3>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $event->course->code }} - {{ $event->course->name }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Created by {{ $event->creator->name ?? 'Unknown' }} on {{ $event->created_at->format('M j, Y') }}
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.events.edit', $event) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Edit Event
                        </a>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                Delete Event
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

