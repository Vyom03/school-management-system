<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User Management') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Add New User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filters -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-4">
                            <div class="flex-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search ?? '' }}"
                                       placeholder="Search by name or email..." 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <select name="role" 
                                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ ($roleFilter ?? '') === $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                                Filter
                            </button>
                            @if($search || $roleFilter)
                                <a href="{{ route('admin.users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-md">
                                    Clear
                                </a>
                            @endif
                        </form>
                    </div>

                    @if($users->count() > 0)
                        <!-- Bulk Delete Form -->
                        <form id="bulkDeleteForm" method="POST" action="{{ route('admin.users.bulk-delete') }}" class="mb-4">
                            @csrf
                            <div class="flex items-center gap-2 mb-4">
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

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                <input type="checkbox" id="selectAllCheckbox" onclick="toggleAll(this)" class="rounded">
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Joined</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($users as $user)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" 
                                                           name="user_ids[]" 
                                                           value="{{ $user->id }}" 
                                                           class="user-checkbox rounded"
                                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $user->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $user->name }}
                                                        @if($user->id === auth()->id())
                                                            <span class="ml-2 text-xs text-green-600 dark:text-green-400">(You)</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $user->email }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $role = $user->roles->first();
                                                        $roleColors = [
                                                            'admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                                            'teacher' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                            'student' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        ];
                                                        $colorClass = $roleColors[$role?->name ?? 'student'] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                        {{ ucfirst($role?->name ?? 'No role') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $user->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                                        Edit
                                                    </a>
                                                    @if($user->id !== auth()->id())
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete user {{ $user->name }}? This will also delete all their related data.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">Delete</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">
                            @if($search || $roleFilter)
                                No users found matching your filters.
                            @else
                                No users found.
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAll(checkbox) {
            const checkboxes = document.querySelectorAll('.user-checkbox:not([disabled])');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('.user-checkbox:not([disabled])');
            checkboxes.forEach(cb => cb.checked = true);
            document.getElementById('selectAllCheckbox').checked = true;
        }

        function deselectAll() {
            const checkboxes = document.querySelectorAll('.user-checkbox:not([disabled])');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('selectAllCheckbox').checked = false;
        }

        function confirmBulkDelete() {
            const checked = document.querySelectorAll('.user-checkbox:checked:not([disabled])');
            if (checked.length === 0) {
                alert('Please select at least one user to delete.');
                return false;
            }
            return confirm(`Are you sure you want to delete ${checked.length} user(s)? This will also delete all their related data. This action cannot be undone.`);
        }
    </script>
</x-app-layout>

