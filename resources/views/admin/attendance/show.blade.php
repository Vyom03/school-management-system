<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $course->code }} - View Attendance
            </h2>
            <a href="{{ route('admin.attendance.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                ← Back to Courses
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Course Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $course->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Instructor: {{ $course->teacher->name ?? 'TBA' }} • {{ $course->semester }} • {{ $enrollments->count() }} students
                    </p>
                </div>
            </div>

            <!-- Date Selector -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.attendance.show', $course) }}" class="flex items-center gap-4">
                        <div class="flex-1">
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Date</label>
                            <input type="date" 
                                   name="date" 
                                   id="date" 
                                   value="{{ $selectedDate->toDateString() }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="pt-6">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                Load Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- PDF Download with Date Range -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Download PDF Report for Date Range</h4>
                    <form method="GET" action="{{ route('admin.reports.attendance.pdf') }}" class="flex flex-wrap gap-4 items-end">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <div class="flex-1 min-w-[200px]">
                            <label for="pdf_start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                            <input type="date" 
                                   id="pdf_start_date" 
                                   name="start_date" 
                                   value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label for="pdf_end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                            <input type="date" 
                                   id="pdf_end_date" 
                                   name="end_date" 
                                   value="{{ request('end_date', now()->format('Y-m-d')) }}"
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-sm transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Attendance Statistics -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Attendance Summary - {{ $selectedDate->format('F j, Y') }}
                        </h3>
                        <a href="{{ route('admin.reports.attendance.pdf', ['course_id' => $course->id, 'date' => $selectedDate->toDateString()]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-sm transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF
                        </a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_students'] }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Total Students</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                            <div class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $stats['present'] }}</div>
                            <div class="text-xs text-green-600 dark:text-green-400 mt-1">Present</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 dark:bg-red-900 rounded-lg">
                            <div class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $stats['absent'] }}</div>
                            <div class="text-xs text-red-600 dark:text-red-400 mt-1">Absent</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats['late'] }}</div>
                            <div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Late</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['excused'] }}</div>
                            <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">Excused</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                            <div class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ $stats['not_marked'] }}</div>
                            <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">Not Marked</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Records -->
            @if($enrollments->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Attendance Records</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notes</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Marked By</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($enrollments as $enrollment)
                                        @php
                                            $attendance = $enrollment->attendances->first();
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    <a href="{{ route('admin.attendance.student', $enrollment->student) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                        {{ $enrollment->student->name }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $enrollment->student->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($attendance)
                                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                        {{ $attendance->status === 'absent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                        {{ $attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                        {{ $attendance->status === 'excused' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}">
                                                        {{ ucfirst($attendance->status) }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                        Not Marked
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $attendance?->notes ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                {{ $attendance?->markedBy->name ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-600 dark:text-gray-400">
                        No students enrolled in this course.
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

