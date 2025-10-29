<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($enrollments->count() > 0)
                @foreach($enrollments as $enrollment)
                    @php
                        $stats = $enrollment->attendance_stats;
                        $percentage = $stats['percentage'];
                        $statusColor = $percentage >= 90 ? 'green' : ($percentage >= 75 ? 'yellow' : 'red');
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Course Header -->
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $enrollment->course->code }} - {{ $enrollment->course->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Instructor: {{ $enrollment->course->teacher->name ?? 'TBA' }} • {{ $enrollment->course->semester }}
                                </p>
                            </div>

                            <!-- Attendance Summary -->
                            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
                                <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Total Classes</div>
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
                                <div class="text-center p-4 bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-900 rounded-lg">
                                    <div class="text-2xl font-bold text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-300">{{ $percentage }}%</div>
                                    <div class="text-xs text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 mt-1">Attendance</div>
                                </div>
                            </div>

                            <!-- Recent Attendance Records -->
                            @if($enrollment->attendances->count() > 0)
                                <div class="mt-6">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Recent Attendance</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-900">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($enrollment->attendances->sortByDesc('date')->take(10) as $attendance)
                                                    <tr>
                                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                            {{ $attendance->date->format('M d, Y') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm">
                                                            <span class="px-2 py-1 rounded text-xs font-semibold
                                                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                                {{ $attendance->status === 'absent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                                {{ $attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                                {{ $attendance->status === 'excused' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}">
                                                                {{ ucfirst($attendance->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $attendance->notes ?? '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-600 dark:text-gray-400">No attendance records yet for this course.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        You are not enrolled in any courses yet.
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

