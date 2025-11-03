@extends('parent.layout', ['header' => '<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Parent Portal Dashboard</h2>'])

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-semibold mb-2">Welcome, {{ $parent->name }}!</h3>
                <p class="text-gray-600 dark:text-gray-400">View your child's academic progress and school information.</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg shadow">
                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Children</h4>
                <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ $totalChildren }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg shadow">
                <h4 class="text-sm font-medium text-green-800 dark:text-green-200">Total Courses</h4>
                <p class="mt-2 text-3xl font-bold text-green-900 dark:text-green-100">{{ $totalCourses }}</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900 p-6 rounded-lg shadow">
                <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200">Email</h4>
                <p class="mt-2 text-sm font-medium text-purple-900 dark:text-purple-100">{{ $parent->email }}</p>
            </div>
        </div>

        <!-- Children Cards -->
        @if($students->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Your Children</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($students as $student)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-lg transition">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $student->name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $student->email }}</p>
                                @if($student->grade_level)
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Grade {{ $student->grade_level }}</p>
                                @endif
                                
                                @php
                                    $enrollments = $student->enrollments;
                                    $totalCourses = $enrollments->count();
                                    $totalGrades = $enrollments->flatMap->grades;
                                    $averageGrade = null;
                                    if ($totalGrades->count() > 0) {
                                        $totalScore = $totalGrades->sum('score');
                                        $totalMaxScore = $totalGrades->sum('max_score');
                                        if ($totalMaxScore > 0) {
                                            $averageGrade = round(($totalScore / $totalMaxScore) * 100, 1);
                                        }
                                    }
                                @endphp
                                
                                <div class="mt-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Courses:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $totalCourses }}</span>
                                    </div>
                                    @if($averageGrade !== null)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 dark:text-gray-400">Average Grade:</span>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $averageGrade }}%</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('parent.student.show', $student->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-600 dark:text-gray-400">
                    <p>No children linked to your account. Please contact the school administrator.</p>
                </div>
            </div>
        @endif

        <!-- Recent Announcements -->
        @if($announcements->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Announcements</h3>
                    <div class="space-y-4">
                        @foreach($announcements as $announcement)
                            <div class="border-l-4 border-indigo-500 pl-4 py-2">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $announcement->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($announcement->content, 150) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">{{ $announcement->created_at->format('M d, Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
