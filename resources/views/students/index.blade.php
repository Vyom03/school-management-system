<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Students') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Import Errors -->
            @if(session('import_errors') && count(session('import_errors')) > 0)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p class="font-semibold mb-2">Import Errors:</p>
                    <ul class="list-disc list-inside text-sm max-h-40 overflow-y-auto">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search and Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('students.index') }}" class="flex-1 flex gap-2">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search ?? '' }}"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md" 
                                       placeholder="Search students...">
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Search
                            </button>
                            @if($search)
                                <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                                    Clear
                                </a>
                            @endif
                        </form>

                        <!-- Export Button -->
                        <div class="flex gap-2">
                            <a href="{{ route('students.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export CSV
                            </a>

                            @role('admin')
                                <button onclick="toggleImport()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Import CSV
                                </button>
                            @endrole
                        </div>
                    </div>

                    <!-- Import Form (Hidden by default, Admin only) -->
                    @role('admin')
                        <div id="importForm" class="hidden mt-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Import Students from CSV</h3>
                            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        CSV File Format: Name, Email (headers optional)
                                    </label>
                                    <input type="file" 
                                           name="csv_file" 
                                           accept=".csv,.txt"
                                           required
                                           class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Upload a CSV file with columns: Name, Email. Default password will be "password".
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                                        Upload and Import
                                    </button>
                                    <button type="button" onclick="toggleImport()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endrole
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        All Students ({{ $students->total() }})
                    </h3>

                    @if($students->count() > 0)
                        <!-- Bulk Delete Form (Admin only) -->
                        @role('admin')
                            <form id="bulkDeleteForm" method="POST" action="{{ route('students.bulk-delete') }}" class="mb-4">
                                @csrf
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        Select All
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" onclick="deselectAll()" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        Deselect All
                                    </button>
                                    <button type="submit" onclick="return confirmBulkDelete()" class="ml-auto bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                                        Delete Selected
                                    </button>
                                </div>
                            
                                <div class="overflow-x-auto mt-4">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-900">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    <input type="checkbox" id="selectAllCheckbox" onclick="toggleAll(this)" class="rounded">
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Enrolled Courses</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Joined</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($students as $student)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox rounded">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $student->id }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $student->name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $student->email }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            {{ $student->enrollments_count }} {{ Str::plural('course', $student->enrollments_count) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $student->created_at->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Delete student {{ $student->name }}? This will also delete their enrollments, grades, and attendance records.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        @else
                            <!-- Teacher View (No Checkboxes, Only Individual Delete) -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Enrolled Courses</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Joined</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($students as $student)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $student->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $student->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $student->email }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $student->enrollments_count }} {{ Str::plural('course', $student->enrollments_count) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $student->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Delete student {{ $student->name }}? This will also delete their enrollments, grades, and attendance records.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endrole

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">
                            @if($search)
                                No students found matching "{{ $search }}"
                            @else
                                No students found.
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        @role('admin')
            function toggleImport() {
                const form = document.getElementById('importForm');
                form.classList.toggle('hidden');
            }

            function toggleAll(checkbox) {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                checkboxes.forEach(cb => cb.checked = checkbox.checked);
            }

            function selectAll() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                checkboxes.forEach(cb => cb.checked = true);
                document.getElementById('selectAllCheckbox').checked = true;
            }

            function deselectAll() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                checkboxes.forEach(cb => cb.checked = false);
                document.getElementById('selectAllCheckbox').checked = false;
            }

            function confirmBulkDelete() {
                const checked = document.querySelectorAll('.student-checkbox:checked');
                if (checked.length === 0) {
                    alert('Please select at least one student to delete.');
                    return false;
                }
                return confirm(`Are you sure you want to delete ${checked.length} student(s)? This will also delete their enrollments, grades, and attendance records. This action cannot be undone.`);
            }
        @endrole
    </script>
</x-app-layout>

