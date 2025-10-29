<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $course->code }} - Mark Attendance
            </h2>
            <a href="{{ route('teacher.attendance.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                ← Back to Courses
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Course Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $course->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $course->semester }} • {{ $enrollments->count() }} students</p>
                </div>
            </div>

            <!-- Date Selector -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('teacher.attendance.show', $course) }}" class="flex items-center gap-4">
                        <div class="flex-1">
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Date</label>
                            <input type="date" 
                                   name="date" 
                                   id="date" 
                                   value="{{ $selectedDate->toDateString() }}"
                                   max="{{ today()->toDateString() }}"
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

            <!-- Attendance Form -->
            @if($enrollments->count() > 0)
                <form method="POST" action="{{ route('teacher.attendance.store', $course) }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ $selectedDate->toDateString() }}">
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    Attendance for {{ $selectedDate->format('F j, Y') }}
                                </h3>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-semibold">
                                    Save Attendance
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Student</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Present</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Absent</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Other</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($enrollments as $enrollment)
                                            @php
                                                $attendance = $enrollment->attendances->first();
                                                $currentStatus = $attendance ? $attendance->status : 'present';
                                                $currentNotes = $attendance ? $attendance->notes : '';
                                            @endphp
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $enrollment->student->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $enrollment->student->email }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="radio" 
                                                           name="attendance[{{ $enrollment->id }}]" 
                                                           value="present"
                                                           {{ $currentStatus === 'present' ? 'checked' : '' }}
                                                           class="w-5 h-5 text-green-600 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 cursor-pointer">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="radio" 
                                                           name="attendance[{{ $enrollment->id }}]" 
                                                           value="absent"
                                                           {{ $currentStatus === 'absent' ? 'checked' : '' }}
                                                           class="w-5 h-5 text-red-600 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 cursor-pointer">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <select name="attendance_other[{{ $enrollment->id }}]" 
                                                            class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                            onchange="if(this.value) { document.querySelector('input[name=\'attendance[{{ $enrollment->id }}]\'][value=\'' + this.value + '\']')?.click(); this.value = ''; }">
                                                        <option value="">Select...</option>
                                                        <option value="late">Late</option>
                                                        <option value="excused">Excused</option>
                                                    </select>
                                                    <div class="mt-1">
                                                        <input type="radio" 
                                                               name="attendance[{{ $enrollment->id }}]" 
                                                               value="late"
                                                               {{ $currentStatus === 'late' ? 'checked' : '' }}
                                                               class="hidden">
                                                        <input type="radio" 
                                                               name="attendance[{{ $enrollment->id }}]" 
                                                               value="excused"
                                                               {{ $currentStatus === 'excused' ? 'checked' : '' }}
                                                               class="hidden">
                                                        @if($currentStatus === 'late')
                                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-200 rounded">Late</span>
                                                        @elseif($currentStatus === 'excused')
                                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 dark:bg-blue-900 dark:text-blue-200 rounded">Excused</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="text" 
                                                           name="notes[{{ $enrollment->id }}]" 
                                                           value="{{ $currentNotes }}"
                                                           placeholder="Optional notes"
                                                           class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-md font-semibold">
                                    Save Attendance
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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

