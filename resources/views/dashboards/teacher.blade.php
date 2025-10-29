<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">Manage your courses, students, and assignments.</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-purple-50 dark:bg-purple-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200">My Courses</h4>
                    <p class="mt-2 text-3xl font-bold text-purple-900 dark:text-purple-100">0</p>
                    <p class="text-xs text-purple-600 dark:text-purple-300 mt-1">Active courses</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Students</h4>
                    <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ \App\Models\User::role('student')->count() }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">Total students</p>
                </div>
                <div class="bg-orange-50 dark:bg-orange-900 p-6 rounded-lg shadow">
                    <h4 class="text-sm font-medium text-orange-800 dark:text-orange-200">Pending Grading</h4>
                    <p class="mt-2 text-3xl font-bold text-orange-900 dark:text-orange-100">0</p>
                    <p class="text-xs text-orange-600 dark:text-orange-300 mt-1">Assignments to grade</p>
                </div>
            </div>

            <!-- Teacher Tools -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">My Tools</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('teacher.gradebook.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">My Courses</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and manage your courses</p>
                        </a>
                        <a href="{{ route('teacher.gradebook.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Gradebook</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Enter and manage student grades</p>
                        </a>
                        <a href="{{ route('students.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Students</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and export student list</p>
                        </a>
                        <a href="{{ route('teacher.attendance.index') }}" class="block p-4 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Attendance</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Mark and track student attendance</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Activity</h3>
                    <p class="text-gray-600 dark:text-gray-400">No recent activity to display.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

