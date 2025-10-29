<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $course->code }} - Gradebook
            </h2>
            <a href="{{ route('teacher.gradebook.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
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
                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $course->description }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        {{ $course->semester }} • {{ $course->credits }} credits • {{ $enrollments->count() }} students
                    </p>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('teacher.gradebook.show', $course) }}" class="flex gap-4">
                        <div class="flex-1">
                            <label for="search" class="sr-only">Search students</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       value="{{ $search ?? '' }}"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md leading-5 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                       placeholder="Search students by name or email...">
                            </div>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                            Search
                        </button>
                        @if($search)
                            <a href="{{ route('teacher.gradebook.show', $course) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Students and Grades -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Student Grades</h3>
                    
                    @if($search && $enrollments->count() == 0)
                        <div class="text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400">No students found matching "{{ $search }}"</p>
                            <a href="{{ route('teacher.gradebook.show', $course) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 mt-2 inline-block">
                                Show all students
                            </a>
                        </div>
                    @elseif($enrollments->count() > 0)
                        <div class="space-y-6">
                            @foreach($enrollments as $enrollment)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $enrollment->student->name }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $enrollment->student->email }}</p>
                                            @if($enrollment->grades->count() > 0)
                                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                                    Average: {{ number_format($enrollment->averageGrade(), 2) }}%
                                                </p>
                                            @endif
                                        </div>
                                        <button onclick="toggleAddGrade{{ $enrollment->id }}()" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                            + Add Grade
                                        </button>
                                    </div>

                                    <!-- Add Grade Form (hidden by default) -->
                                    <div id="addGrade{{ $enrollment->id }}" class="hidden bg-gray-50 dark:bg-gray-900 p-4 rounded mb-4">
                                        <form action="{{ route('teacher.gradebook.store', $enrollment) }}" method="POST">
                                            @csrf
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assignment</label>
                                                    <input type="text" name="assignment_name" required
                                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Score</label>
                                                    <input type="number" step="0.01" name="score" required
                                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Score</label>
                                                    <input type="number" step="0.01" name="max_score" value="100" required
                                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comments</label>
                                                    <input type="text" name="comments"
                                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                </div>
                                            </div>
                                            <div class="mt-4 flex gap-2">
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                                                    Save Grade
                                                </button>
                                                <button type="button" onclick="toggleAddGrade{{ $enrollment->id }}()" 
                                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Existing Grades -->
                                    @if($enrollment->grades->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-900">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Assignment</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Score</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Percentage</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Grade</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Comments</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($enrollment->grades as $grade)
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $grade->assignment_name }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $grade->score }}/{{ $grade->max_score }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ number_format($grade->percentage, 2) }}%</td>
                                                            <td class="px-4 py-2 text-sm">
                                                                <span class="px-2 py-1 rounded text-xs font-semibold
                                                                    {{ $grade->letter_grade == 'A' ? 'bg-green-100 text-green-800' : '' }}
                                                                    {{ $grade->letter_grade == 'B' ? 'bg-blue-100 text-blue-800' : '' }}
                                                                    {{ $grade->letter_grade == 'C' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                    {{ $grade->letter_grade == 'D' ? 'bg-orange-100 text-orange-800' : '' }}
                                                                    {{ $grade->letter_grade == 'F' ? 'bg-red-100 text-red-800' : '' }}">
                                                                    {{ $grade->letter_grade }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $grade->comments ?? '-' }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $grade->created_at->format('M d, Y') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">No grades yet.</p>
                                    @endif
                                </div>

                                <script>
                                    function toggleAddGrade{{ $enrollment->id }}() {
                                        const form = document.getElementById('addGrade{{ $enrollment->id }}');
                                        form.classList.toggle('hidden');
                                    }
                                </script>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No students enrolled in this course yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

