<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $assignment->title }}
            </h2>
            <a href="{{ route('student.assignments.index') }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md">
                ← Back to Assignments
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Assignment Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Assignment Details</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Course:</span>
                            <span class="text-gray-900 dark:text-gray-100 ml-2">{{ $assignment->course->name }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Teacher:</span>
                            <span class="text-gray-900 dark:text-gray-100 ml-2">{{ $assignment->teacher->name }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Due Date:</span>
                            <span class="text-gray-900 dark:text-gray-100 ml-2">{{ $assignment->due_date->format('M d, Y g:i A') }}</span>
                            @if($assignment->due_date < now())
                                <span class="ml-2 text-red-600 font-semibold">(Overdue)</span>
                            @elseif($assignment->due_date <= now()->addDay())
                                <span class="ml-2 text-yellow-600 font-semibold">(Due Soon)</span>
                            @endif
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Max Score:</span>
                            <span class="text-gray-900 dark:text-gray-100 ml-2">{{ $assignment->max_score }} points</span>
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

                    @if($assignment->allowed_file_types || $assignment->max_file_size)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">File Requirements:</h4>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                @if($assignment->allowed_file_types)
                                    <li>Allowed file types: <span class="font-medium">{{ $assignment->allowed_file_types }}</span></li>
                                @else
                                    <li>All file types are allowed</li>
                                @endif
                                @if($assignment->max_file_size)
                                    <li>Maximum file size: <span class="font-medium">{{ number_format($assignment->max_file_size / 1024, 1) }} MB</span></li>
                                @else
                                    <li>Maximum file size: <span class="font-medium">10 MB</span> (default)</li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    @if($submission && $submission->score !== null)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Your Grade:</h4>
                            <div class="flex items-center gap-4">
                                <div>
                                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $submission->score }} / {{ $assignment->max_score }}
                                    </span>
                                    <span class="text-lg text-gray-600 dark:text-gray-400 ml-2">
                                        ({{ number_format($submission->percentage, 1) }}%)
                                    </span>
                                </div>
                                @if($submission->letter_grade)
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $submission->letter_grade == 'A' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $submission->letter_grade == 'B' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        {{ $submission->letter_grade == 'C' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                        {{ $submission->letter_grade == 'D' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                        {{ $submission->letter_grade == 'F' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        {{ $submission->letter_grade }}
                                    </span>
                                @endif
                            </div>
                            @if($submission->feedback)
                                <div class="mt-2">
                                    <h5 class="font-medium text-gray-700 dark:text-gray-300 mb-1">Feedback:</h5>
                                    <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $submission->feedback }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submission Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Your Submission</h3>
                    
                    @if($submission && $submission->submitted_at)
                        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Submitted on:</strong> {{ $submission->submitted_at->format('M d, Y g:i A') }}
                                @if($submission->is_late)
                                    <span class="ml-2 text-red-600 font-semibold">(Late submission)</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    <form action="{{ $submission ? route('submissions.update', $submission) : route('submissions.store', $assignment) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($submission)
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Text Content (Optional)</label>
                            <textarea name="content" id="content" rows="6"
                                      class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('content', $submission->content ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">You can type your assignment here, upload files, or both.</p>
                        </div>

                        <div class="mb-4">
                            <label for="files" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Files</label>
                            <input type="file" name="files[]" id="files" multiple
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">You can upload multiple files (max 10 files, 10MB each)</p>
                        </div>

                        @if($submission && $submission->files->count() > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Uploaded Files</label>
                                <div class="space-y-2">
                                    @foreach($submission->files as $file)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $file->original_filename }}</p>
                                                    <p class="text-xs text-gray-500">{{ $file->file_size_human }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('submissions.files.download', $file) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm">
                                                    Download
                                                </a>
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="delete_files[]" value="{{ $file->id }}" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                                                    <span class="ml-2 text-sm text-red-600">Delete</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end space-x-3">
                            <button type="submit" name="save" value="draft" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 px-6 py-2 rounded-md">
                                Save Draft
                            </button>
                            @if(!$submission || $submission->status == 'draft')
                                <button type="submit" name="submit" value="1" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                    Submit Assignment
                                </button>
                            @else
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md" disabled>
                                    Already Submitted
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

