<x-guest-layout>
    <!-- Page Title -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Parent Portal Registration</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Create an account to view your child's information</p>
    </div>

    <!-- Info Notice -->
    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
        <p class="text-xs text-blue-800 dark:text-blue-200">
            <strong>Important:</strong> You need a registration code provided by the school to create an account. 
            Contact the school administration if you don't have a code.
        </p>
    </div>

    <form method="POST" action="{{ route('parent.register') }}" class="space-y-6">
        @csrf

        <!-- Registration Code -->
        <div>
            <x-input-label for="registration_code" :value="__('Registration Code')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <x-text-input 
                    id="registration_code" 
                    class="block w-full pl-10 pr-3 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg transition font-mono" 
                    type="text" 
                    name="registration_code" 
                    :value="old('registration_code')" 
                    required 
                    autofocus
                    placeholder="XXXXXXXX" 
                    maxlength="8"
                    oninput="this.value = this.value.toUpperCase()"
                />
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the 8-character code provided by the school</p>
            <x-input-error :messages="$errors->get('registration_code')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Must match the email associated with the registration code (if specified)</p>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone (Optional) -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number (Optional)')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Address (Optional) -->
        <div>
            <x-input-label for="address" :value="__('Address (Optional)')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" autocomplete="street-address" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <div>
            <x-primary-button class="w-full justify-center">
                {{ __('Create Parent Account') }}
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('parent.login') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                    Sign In
                </a>
            </p>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                Back to School Login
            </a>
        </div>
    </form>
</x-guest-layout>

