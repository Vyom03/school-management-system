<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Calendar') }}
            </h2>
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Event
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Calendar Navigation -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 flex justify-between items-center">
                    <a href="{{ route('calendar.index', ['month' => $startDate->copy()->subMonth()->format('m'), 'year' => $startDate->copy()->subMonth()->format('Y')]) }}" 
                       class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition">
                        ← Previous
                    </a>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $startDate->format('F Y') }}
                    </h3>
                    <a href="{{ route('calendar.index', ['month' => $startDate->copy()->addMonth()->format('m'), 'year' => $startDate->copy()->addMonth()->format('Y')]) }}" 
                       class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition">
                        Next →
                    </a>
                    <a href="{{ route('calendar.index') }}" 
                       class="ml-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        Today
                    </a>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Day Headers -->
                    <div class="grid grid-cols-7 gap-2 mb-2">
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="text-center font-semibold text-gray-700 dark:text-gray-300 py-2">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 gap-2">
                        @php
                            $firstDay = $startDate->copy()->startOfMonth();
                            $lastDay = $startDate->copy()->endOfMonth();
                            $startPadding = $firstDay->dayOfWeek;
                            $daysInMonth = $lastDay->day;
                            $today = now();
                        @endphp

                        <!-- Empty cells for days before month starts -->
                        @for($i = 0; $i < $startPadding; $i++)
                            <div class="aspect-square bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700"></div>
                        @endfor

                        <!-- Days of the month -->
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $currentDate = $firstDay->copy()->addDays($day - 1);
                                $dateKey = $currentDate->format('Y-m-d');
                                $dayEvents = $eventsByDate[$dateKey] ?? [];
                                $isToday = $currentDate->format('Y-m-d') === $today->format('Y-m-d');
                            @endphp
                            <div class="aspect-square bg-white dark:bg-gray-800 rounded-lg border-2 {{ $isToday ? 'border-blue-500 dark:border-blue-400' : 'border-gray-200 dark:border-gray-700' }} p-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-sm font-semibold {{ $isToday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-gray-100' }}">
                                        {{ $day }}
                                    </span>
                                </div>
                                <div class="space-y-1 overflow-y-auto max-h-20">
                                    @foreach($dayEvents as $event)
                                        <div class="group relative text-xs rounded {{ 
                                            $event->type === 'academic' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 
                                            ($event->type === 'holiday' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                                            ($event->type === 'exam' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                                            ($event->type === 'meeting' ? 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200' : 
                                            'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200')))
                                        }} hover:opacity-90 transition p-1">
                                            <div class="flex items-start justify-between gap-1">
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium truncate" title="{{ $event->title }}">
                                                        {{ Str::limit($event->title, 10) }}
                                                    </div>
                                                    @if(!$event->is_all_day && $event->start_time)
                                                        <div class="text-xs opacity-75">
                                                            @php
                                                                try {
                                                                    $time = is_string($event->start_time) ? \Carbon\Carbon::createFromTimeString($event->start_time) : \Carbon\Carbon::parse($event->start_time);
                                                                    echo $time->format('g:i A');
                                                                } catch (\Exception $e) {
                                                                    echo $event->start_time;
                                                                }
                                                            @endphp
                                                        </div>
                                                    @endif
                                                </div>
                                                @auth
                                                    @if(auth()->user()->hasRole('admin'))
                                                        <a href="{{ route('admin.events.edit', $event) }}" 
                                                           class="flex-shrink-0 inline-flex items-center justify-center w-6 h-6 text-xs text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded shadow-md hover:shadow-lg transition border border-blue-700 dark:border-blue-400"
                                                           title="Edit Event">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endfor

                        <!-- Empty cells for days after month ends -->
                        @php
                            $endPadding = 6 - $lastDay->dayOfWeek;
                        @endphp
                        @for($i = 0; $i < $endPadding; $i++)
                            <div class="aspect-square bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700"></div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            @php
                $upcomingEvents = \App\Models\Event::visibleTo(auth()->user())
                    ->where('start_date', '>=', now())
                    ->orderBy('start_date')
                    ->orderBy('start_time')
                    ->limit(10)
                    ->get();
            @endphp

            @if($upcomingEvents->count() > 0)
                <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upcoming Events</h3>
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('admin.events.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        Manage All Events →
                                    </a>
                                @endif
                            @endauth
                        </div>
                        <div class="space-y-3">
                            @foreach($upcomingEvents as $event)
                                <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="w-12 h-12 rounded-lg flex flex-col items-center justify-center {{ 
                                            $event->type === 'academic' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 
                                            ($event->type === 'holiday' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                                            ($event->type === 'exam' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                                            ($event->type === 'meeting' ? 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200' : 
                                            'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200')))
                                        }}">
                                            <span class="text-xs font-semibold">{{ $event->start_date->format('M') }}</span>
                                            <span class="text-lg font-bold">{{ $event->start_date->format('d') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $event->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $event->formatted_date_range }}
                                            @if($event->course)
                                                • {{ $event->course->code }}
                                            @endif
                                        </p>
                                        @if($event->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($event->description, 100) }}</p>
                                        @endif
                                    </div>
                                    @auth
                                        @if(auth()->user()->hasRole('admin'))
                                            <div class="flex-shrink-0 ml-3 flex gap-2">
                                                <a href="{{ route('admin.events.edit', $event) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 rounded-lg transition"
                                                   title="Edit Event">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 rounded-lg transition"
                                                            title="Delete Event">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

