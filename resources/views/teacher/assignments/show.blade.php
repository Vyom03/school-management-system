<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $assignment->title }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    Edit
                </a>
                <a href="{{ route('teacher.assignments.index') }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Assignment Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Assignment Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Course:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $assignment->course->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Due Date:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $assignment->due_date->format('M d, Y g:i A') }}</span>
                                @if($assignment->is_overdue)
                                    <span class="ml-2 text-red-600 font-semibold">(Overdue)</span>
                                @elseif($assignment->is_due_soon)
                                    <span class="ml-2 text-yellow-600 font-semibold">(Due Soon)</span>
                                @endif
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Max Score:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $assignment->max_score }} points</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                                @if($assignment->is_published)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        Draft
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($assignment->description)
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-1">Description:</h4>
                            <p class="text-gray-900 dark:text-gray-100">{{ $assignment->description }}</p>
                        </div>
                    @endif
                    
                    @if($assignment->instructions)
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-1">Instructions:</h4>
                            <div class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $assignment->instructions }}</div>
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $assignment->submission_count }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Submitted</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $assignment->graded_count }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Graded</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $assignment->course->enrollments_count - $assignment->submission_count }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submissions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Student Submissions</h3>
                    
                    @if($submissions->count() > 0)
                        <div class="space-y-4">
                            @foreach($submissions as $submission)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <!-- Submission Header -->
                                    <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 cursor-pointer" onclick="toggleSubmission({{ $submission->id }})">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <svg id="icon-{{ $submission->id }}" class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $submission->student->name }}</h4>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $submission->student->email }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-6">
                                                <div class="text-right">
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                                        @if($submission->submitted_at)
                                                            {{ $submission->submitted_at->format('M d, Y g:i A') }}
                                                            @if($submission->is_late)
                                                                <span class="ml-2 text-red-600 text-xs font-semibold">(Late)</span>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-400">Not submitted</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    @if($submission->status == 'draft')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">Draft</span>
                                                    @elseif($submission->status == 'submitted')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Submitted</span>
                                                    @elseif($submission->status == 'graded')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Graded</span>
                                                    @elseif($submission->status == 'returned')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Returned</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    @if($submission->score !== null)
                                                        <span class="font-semibold">{{ $submission->score }} / {{ $assignment->max_score }}</span>
                                                        <span class="text-xs">({{ number_format($submission->percentage, 1) }}%)</span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submission Content (Hidden by default) -->
                                    <div id="submission-{{ $submission->id }}" class="hidden px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                                        <div class="space-y-6">
                                            <!-- Submission Content -->
                                            @if($submission->content)
                                                <div>
                                                    <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Text Content:</h5>
                                                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg whitespace-pre-wrap text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $submission->content }}
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Uploaded Files -->
                                            @if($submission->files->count() > 0)
                                                <div>
                                                    <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Uploaded Files:</h5>
                                                    <div class="space-y-2">
                                                        @foreach($submission->files as $file)
                                                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                                                <div class="flex items-center space-x-3">
                                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $file->original_filename }}</p>
                                                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $file->file_size_human }}</p>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ route('submissions.files.download', $file) }}" 
                                                                   class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                    Download
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if(!$submission->content && $submission->files->count() == 0)
                                                <p class="text-sm text-gray-500 dark:text-gray-400">No content or files submitted yet.</p>
                                            @endif

                                            <!-- Grading Form -->
                                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Grade Submission:</h5>
                                                <form action="{{ route('submissions.grade', $submission) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    <div class="grid grid-cols-3 gap-4">
                                                        <div>
                                                            <label for="score-{{ $submission->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Score *</label>
                                                            <input type="number" 
                                                                   name="score" 
                                                                   id="score-{{ $submission->id }}" 
                                                                   value="{{ old('score', $submission->score) }}" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   max="{{ $assignment->max_score }}" 
                                                                   required
                                                                   class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                            <p class="mt-1 text-xs text-gray-500">Max: {{ $assignment->max_score }}</p>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label for="feedback-{{ $submission->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Feedback</label>
                                                            <textarea name="feedback" 
                                                                      id="feedback-{{ $submission->id }}" 
                                                                      rows="3"
                                                                      class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('feedback', $submission->feedback) }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                                                            {{ $submission->score !== null ? 'Update Grade' : 'Submit Grade' }}
                                                        </button>
                                                    </div>
                                                </form>

                                                @if($submission->score !== null && $submission->graded_at)
                                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            Graded on {{ $submission->graded_at->format('M d, Y g:i A') }}
                                                            @if($submission->grader)
                                                                by {{ $submission->grader->name }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No submissions yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSubmission(submissionId) {
            const content = document.getElementById('submission-' + submissionId);
            const icon = document.getElementById('icon-' + submissionId);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // Auto-expand submissions that need grading
        @foreach($submissions as $submission)
            @if($submission->status == 'submitted' && $submission->score === null)
                toggleSubmission({{ $submission->id }});
            @endif
        @endforeach
    </script>
</x-app-layout>

