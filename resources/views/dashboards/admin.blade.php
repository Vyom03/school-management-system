<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Welcome, Administrator!</h3>
                    <p class="text-gray-600 dark:text-gray-400">You have full access to manage the school system.</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Total Users</h4>
                    <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-green-800 dark:text-green-200">Teachers</h4>
                    <p class="mt-2 text-3xl font-bold text-green-900 dark:text-green-100">{{ \App\Models\User::role('teacher')->count() }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Students</h4>
                    <p class="mt-2 text-3xl font-bold text-yellow-900 dark:text-yellow-100">{{ \App\Models\User::role('student')->count() }}</p>
                </div>
            </div>

            <!-- Admin Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Admin Tools</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Manage Users</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create, edit, and manage all users (admins, teachers, students)</p>
                        </a>
                        <a href="{{ route('students.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Manage Students</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View, import, and export student data</p>
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Attendance</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View attendance records across all courses</p>
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Manage Courses</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create and manage courses and curricula</p>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">View Reports</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Access comprehensive system reports</p>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">System Settings</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure school-wide settings</p>
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Manage Announcements</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create, edit, and publish announcements</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

