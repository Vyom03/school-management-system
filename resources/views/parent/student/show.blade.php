@extends('parent.layout', ['header' => '<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">' . $student->name . '\'s Information</h2>'])

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('parent.dashboard') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Student Info Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Student Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Name</p>
                        <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $student->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                        <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $student->email }}</p>
                    </div>
                    @if($student->grade_level)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Grade Level</p>
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">Grade {{ $student->grade_level }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg shadow">
                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Courses</h4>
                <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ $enrollments->count() }}</p>
            </div>
            @if($averageGrade !== null)
                <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-green-800 dark:text-green-200">Average Grade</h4>
                    <p class="mt-2 text-3xl font-bold text-green-900 dark:text-green-100">{{ $averageGrade }}%</p>
                </div>
            @endif
            <div class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg shadow">
                <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Attendance</h4>
                <p class="mt-2 text-3xl font-bold text-yellow-900 dark:text-yellow-100">{{ $attendancePercentage }}%</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900 p-6 rounded-lg shadow">
                <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200">Outstanding Fees</h4>
                <p class="mt-2 text-3xl font-bold text-purple-900 dark:text-purple-100">${{ number_format($pendingFees, 2) }}</p>
            </div>
        </div>

        <!-- Grades Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Grades by Course</h3>
                @if($enrollments->count() > 0)
                    <div class="space-y-6">
                        @foreach($enrollments as $enrollment)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $enrollment->course->code }} - {{ $enrollment->course->name }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Instructor: {{ $enrollment->course->teacher->name ?? 'TBA' }}
                                </p>
                                
                                @if($enrollment->grades->count() > 0)
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Course Average: <span class="text-green-600 dark:text-green-400">{{ number_format($enrollment->averageGrade(), 2) }}%</span>
                                        </p>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-900">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Assignment</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Score</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($enrollment->grades as $grade)
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $grade->assignment_name ?? 'N/A' }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $grade->score }}/{{ $grade->max_score }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $grade->created_at->format('M d, Y') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">No grades available yet.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">No enrolled courses.</p>
                @endif
            </div>
        </div>

        <!-- Attendance Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Attendance by Course</h3>
                @if($enrollments->count() > 0)
                    <div class="space-y-6">
                        @foreach($enrollments as $enrollment)
                            @php
                                $stats = $enrollment->attendanceStats();
                                $percentage = $stats['percentage'];
                            @endphp
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $enrollment->course->code }} - {{ $enrollment->course->name }}
                                    </h4>
                                    <span class="text-sm font-medium {{ $percentage >= 90 ? 'text-green-600' : ($percentage >= 75 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($percentage, 1) }}%
                                    </span>
                                </div>
                                <div class="grid grid-cols-4 gap-4 mt-4">
                                    <div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Present</p>
                                        <p class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $stats['present'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Absent</p>
                                        <p class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $stats['absent'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Late</p>
                                        <p class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ $stats['late'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Excused</p>
                                        <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ $stats['excused'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">No attendance records available.</p>
                @endif
            </div>
        </div>

        <!-- Fees Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Fee Information</h3>
                @if($totalFees->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fee Type</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Due Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($totalFees as $fee)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $fee->feeStructure->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">${{ number_format($fee->amount, 2) }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $fee->due_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($fee->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($fee->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($fee->status === 'overdue') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                @endif">
                                                {{ ucfirst($fee->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">No fees assigned.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

