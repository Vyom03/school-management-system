<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Assignments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('student.assignments.index') }}" class="flex gap-4">
                        <div class="flex-1">
                            <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Course</label>
                            <select name="course_id" id="course_id" onchange="this.form.submit()"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Status</label>
                            <select name="status" id="status" onchange="this.form.submit()"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Assignments</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search assignments..."
                                   class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($assignments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Assignment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Course</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($assignments as $assignment)
                                        @php
                                            $submission = $assignment->submissions()->where('student_id', auth()->id())->first();
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $assignment->title }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $assignment->course->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                <div>{{ $assignment->due_date->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $assignment->due_date->format('g:i A') }}</div>
                                                @if($assignment->due_date < now())
                                                    <span class="text-xs text-red-600 font-semibold">Overdue</span>
                                                @elseif($assignment->due_date <= now()->addDay())
                                                    <span class="text-xs text-yellow-600 font-semibold">Due Soon</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($submission && $submission->status != 'draft')
                                                    @if($submission->status == 'submitted')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            Submitted
                                                        </span>
                                                    @elseif($submission->status == 'graded')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            Graded
                                                        </span>
                                                    @elseif($submission->status == 'returned')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            Returned
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Not Submitted
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                @if($submission && $submission->score !== null)
                                                    {{ $submission->score }} / {{ $assignment->max_score }}
                                                    <span class="text-xs text-gray-500">({{ number_format($submission->percentage, 1) }}%)</span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('student.assignments.show', $assignment) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                                    {{ $submission ? 'View/Edit' : 'Submit' }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $assignments->links() }}
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No assignments found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

