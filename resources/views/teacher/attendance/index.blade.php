<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance - My Courses') }}
        </h2>
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
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Select a Course to Mark Attendance</h3>
                    
                    @if($courses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($courses as $course)
                                <a href="{{ route('teacher.attendance.show', $course) }}" 
                                   class="block p-6 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $course->code }}</h4>
                                            <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $course->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $course->semester }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-semibold">
                                                {{ $course->enrollments_count }} {{ Str::plural('student', $course->enrollments_count) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No courses assigned yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

