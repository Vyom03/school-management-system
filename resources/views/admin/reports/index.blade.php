<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('System Reports & Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- System Overview -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">System Overview</h3>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <div class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['total_students'] }}</div>
                            <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">Students</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                            <div class="text-3xl font-bold text-purple-700 dark:text-purple-300">{{ $stats['total_teachers'] }}</div>
                            <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">Teachers</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                            <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $stats['total_courses'] }}</div>
                            <div class="text-xs text-green-600 dark:text-green-400 mt-1">Courses</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                            <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats['total_enrollments'] }}</div>
                            <div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Enrollments</div>
                        </div>
                        <div class="text-center p-4 bg-indigo-50 dark:bg-indigo-900 rounded-lg">
                            <div class="text-3xl font-bold text-indigo-700 dark:text-indigo-300">{{ $stats['attendance_today'] }}</div>
                            <div class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">Attendance Today</div>
                        </div>
                        <div class="text-center p-4 bg-pink-50 dark:bg-pink-900 rounded-lg">
                            <div class="text-3xl font-bold text-pink-700 dark:text-pink-300">{{ $stats['grades_entered'] }}</div>
                            <div class="text-xs text-pink-600 dark:text-pink-400 mt-1">Grades Entered</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links to Detailed Reports -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Detailed Reports</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('admin.reports.attendance') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">📊 Attendance Report</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View attendance statistics by course</p>
                        </a>
                        <a href="{{ route('admin.reports.grades') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">📈 Grades Report</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View grade statistics by course</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Real-Time Analytics Dashboard (Vue.js) -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">📊 Real-Time Analytics</h3>
                    <div id="analytics-dashboard">
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="mt-2">Loading analytics dashboard...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Enrollments -->
            @if($recent_enrollments->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Enrollments</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Student</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Course</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recent_enrollments as $enrollment)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $enrollment->student->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $enrollment->course->code }} - {{ $enrollment->course->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $enrollment->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    @vite('resources/js/analytics.js')
    @endpush
</x-app-layout>

