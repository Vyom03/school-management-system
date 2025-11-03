<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Generate Parent Registration Code') }}
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
                    <form method="POST" action="{{ route('admin.parent-codes.store') }}" class="space-y-6">
                        @csrf

                        <!-- Student Selection -->
                        <div>
                            <x-input-label for="student_id" :value="__('Student')" />
                            <select name="student_id" id="student_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a student...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <!-- Email (Optional - for pre-approved email) -->
                        <div>
                            <x-input-label for="email" :value="__('Pre-approved Email (Optional)')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                If provided, only this email can use the code. Leave empty to allow any email.
                            </p>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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

                        <!-- Number of Codes to Generate -->
                        <div>
                            <x-input-label for="count" :value="__('Number of Codes')" />
                            <x-text-input id="count" class="block mt-1 w-full" type="number" name="count" :value="old('count', 1)" min="1" max="10" required />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Generate multiple codes for the same student (e.g., one for each parent).
                            </p>
                            <x-input-error :messages="$errors->get('count')" class="mt-2" />
                        </div>

                        <!-- Expiration Date (Optional) -->
                        <div>
                            <x-input-label for="expires_at" :value="__('Expiration Date (Optional)')" />
                            <x-text-input id="expires_at" class="block mt-1 w-full" type="date" name="expires_at" :value="old('expires_at')" min="{{ date('Y-m-d') }}" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Leave empty to expire after 6 months from today.
                            </p>
                            <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Generate Code(s)') }}
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

