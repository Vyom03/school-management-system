<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bulk Generate Parent Registration Codes') }}
            </h2>
            <a href="{{ route('admin.parent-codes.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Info Notice -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>Bulk Generation:</strong> This will generate registration codes for all students in the selected grade level. 
                            The codes will be automatically downloaded as a PDF document for easy distribution to parents.
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-200">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.parent-codes.bulk-store') }}" class="space-y-6">
                        @csrf

                        <!-- Grade Level Selection -->
                        <div>
                            <x-input-label for="grade_level" :value="__('Grade Level / Class')" />
                            <select name="grade_level" id="grade_level" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a grade level...</option>
                                @foreach($gradeLevels as $level => $label)
                                    <option value="{{ $level }}" {{ old('grade_level') == $level ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Select the grade level to generate codes for all students in that class.
                            </p>
                            <x-input-error :messages="$errors->get('grade_level')" class="mt-2" />
                        </div>

                        <!-- Codes Per Student -->
                        <div>
                            <x-input-label for="codes_per_student" :value="__('Codes Per Student')" />
                            <x-text-input id="codes_per_student" class="block mt-1 w-full" type="number" name="codes_per_student" :value="old('codes_per_student', 1)" min="1" max="5" required />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Number of registration codes to generate for each student (e.g., 2 for both parents). Maximum 5 codes per student.
                            </p>
                            <x-input-error :messages="$errors->get('codes_per_student')" class="mt-2" />
                        </div>

                        <!-- Relationship -->
                        <div>
                            <x-input-label for="relationship" :value="__('Relationship')" />
                            <select name="relationship" id="relationship" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="parent" {{ old('relationship') == 'parent' ? 'selected' : '' }}>Parent</option>
                                <option value="guardian" {{ old('relationship') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                                <option value="other" {{ old('relationship') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('relationship')" class="mt-2" />
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <x-input-label for="expires_at" :value="__('Expiration Date')" />
                            <x-text-input id="expires_at" class="block mt-1 w-full" type="date" name="expires_at" :value="old('expires_at')" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                All codes will expire on this date. Must be a future date.
                            </p>
                            <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center gap-4">
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Generate Codes & Download PDF') }}
                            </x-primary-button>
                            <a href="{{ route('admin.parent-codes.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

