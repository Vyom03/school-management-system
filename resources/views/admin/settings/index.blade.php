<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- School Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">School Information</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">School Name</p>
                            <p class="text-gray-900 dark:text-gray-100">Bright Future Academy</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Academic Year</p>
                            <p class="text-gray-900 dark:text-gray-100">2024-2025</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Contact Email</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ env('CONTACT_RECIPIENT', 'admin@school.com') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Configuration -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">System Configuration</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Environment</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ app()->environment() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Laravel Version</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ app()->version() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">PHP Version</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ phpversion() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.courses.index') }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400">
                            → Manage Courses
                        </a>
                        <a href="{{ route('students.index') }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400">
                            → Manage Students
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400">
                            → View Reports
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400">
                            → View Attendance
                        </a>
                    </div>
                </div>
            </div>

            <!-- Note -->
            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Note:</strong> Advanced settings can be configured in the <code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">.env</code> file. 
                    Contact your system administrator for environment-specific configurations.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

