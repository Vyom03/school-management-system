<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">Ready to continue your learning journey?</p>
                </div>
            </div>

            <!-- Quick Stats -->
            @php
                $myEnrollments = Auth::user()->enrollments;
                $totalGrades = $myEnrollments->flatMap(function($enrollment) {
                    return $enrollment->grades;
                });
                $averageGrade = $totalGrades->count() > 0 ? round($totalGrades->avg('grade'), 1) : null;
                $attendanceCount = \App\Models\Attendance::whereIn('enrollment_id', $myEnrollments->pluck('id'))
                    ->where('status', 'present')
                    ->count();
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-indigo-50 dark:bg-indigo-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Enrolled Courses</h4>
                    <p class="mt-2 text-3xl font-bold text-indigo-900 dark:text-indigo-100">{{ $myEnrollments->count() }}</p>
                    <p class="text-xs text-indigo-600 dark:text-indigo-300 mt-1">Active courses</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-green-800 dark:text-green-200">Attendance</h4>
                    <p class="mt-2 text-3xl font-bold text-green-900 dark:text-green-100">{{ $attendanceCount }}</p>
                    <p class="text-xs text-green-600 dark:text-green-300 mt-1">Days present</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Average Grade</h4>
                    <p class="mt-2 text-3xl font-bold text-yellow-900 dark:text-yellow-100">
                        @if($averageGrade)
                            {{ $averageGrade }}%
                        @else
                            --
                        @endif
                    </p>
                    <p class="text-xs text-yellow-600 dark:text-yellow-300 mt-1">Overall average</p>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('student.grades.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">My Courses</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Access your enrolled courses</p>
                        </a>
                        <a href="#" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Assignments</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and submit assignments</p>
                        </a>
                        <a href="{{ route('student.grades.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Grades</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Check your current grades</p>
                        </a>
                        <a href="{{ route('student.attendance.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Attendance</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View your attendance records</p>
                        </a>
                        <a href="#" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Schedule</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View your class schedule</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- My Courses -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">My Courses</h3>
                    @if($myEnrollments->count() > 0)
                        <div class="space-y-3">
                            @foreach($myEnrollments as $enrollment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $enrollment->course->name }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            Teacher: {{ $enrollment->course->teacher->name ?? 'Not Assigned' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $avg = $enrollment->averageGrade();
                                        @endphp
                                        @if($avg)
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ round($avg, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">No grades yet</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">You are not enrolled in any courses yet.</p>
                    @endif
                </div>
            </div>

            <!-- Announcements -->
            @php
                $announcements = \App\Models\Announcement::where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            @endphp
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Announcements</h3>
                    @if($announcements->count() > 0)
                        <div class="space-y-3">
                            @foreach($announcements as $announcement)
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $announcement->title }}
                                    </h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        {{ Str::limit($announcement->content, 100) }}
                                    </p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $announcement->created_at->diffForHumans() }}
                                        </span>
                                        <a href="{{ route('announcements.show', $announcement) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            Read more →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('announcements.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                View all announcements →
                            </a>
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No announcements at this time.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

