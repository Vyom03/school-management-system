<x-guest-layout>
    <!-- Login Type Toggle -->
    <div class="mb-6">
        <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
            <button type="button" onclick="switchToSchoolLogin()" id="school-login-btn" class="flex-1 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 bg-indigo-600 text-white">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    School Login
                </span>
            </button>
            <button type="button" onclick="switchToParentLogin()" id="parent-login-btn" class="flex-1 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Parent Login
                </span>
            </button>
        </div>
    </div>

    <!-- Page Title -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="login-title">Sign In</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400" id="login-subtitle">Enter your credentials to access your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 pr-3 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg transition" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="student@school.edu" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold text-gray-700 dark:text-gray-300" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password" class="block w-full pl-10 pr-3 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg transition"
                                type="password"
                                name="password"
                                required autocomplete="current-password" 
                                placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div>
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:ring-indigo-500">
                {{ __('Sign In') }}
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-6" id="register-link-section">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Don't have an account?
                <a href="{{ route('register') }}" id="register-link" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                    Create Account
                </a>
            </p>
        </div>
    </form>

    <!-- Quick Access Info -->
    <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800" id="demo-accounts">
        <p class="text-xs font-semibold text-blue-900 dark:text-blue-100 mb-2">Demo Accounts:</p>
        <div class="text-xs text-blue-800 dark:text-blue-200 space-y-1">
            <p><strong>Admin:</strong> admin1@test.com / password</p>
            <p><strong>Teacher:</strong> teacher1@test.com / password</p>
            <p><strong>Student:</strong> student1@test.com / password</p>
        </div>
    </div>

    <script>
        let isParentLogin = false;

        function switchToParentLogin() {
            isParentLogin = true;
            document.getElementById('school-login-btn').classList.remove('bg-indigo-600', 'text-white');
            document.getElementById('school-login-btn').classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            document.getElementById('parent-login-btn').classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            document.getElementById('parent-login-btn').classList.add('bg-indigo-600', 'text-white');
            
            document.getElementById('login-title').textContent = 'Parent Portal Login';
            document.getElementById('login-subtitle').textContent = 'Enter your credentials to view your child\'s information';
            document.getElementById('login-form').action = '{{ route("parent.login") }}';
            document.getElementById('email').placeholder = 'parent@example.com';
            
            const demoAccounts = document.getElementById('demo-accounts');
            if (demoAccounts) {
                demoAccounts.innerHTML = `
                    <p class="text-xs font-semibold text-blue-900 dark:text-blue-100 mb-2">Parent Demo Account:</p>
                    <div class="text-xs text-blue-800 dark:text-blue-200 space-y-1">
                        <p><strong>Parent:</strong> parent1@test.com / password</p>
                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">(Available after parent accounts are created)</p>
                    </div>
                `;
            }
            
            // Update register link for parent
            const registerLink = document.getElementById('register-link');
            if (registerLink) {
                registerLink.href = '{{ route("parent.register") }}';
                registerLink.textContent = 'Create Parent Account';
            }
        }

        function switchToSchoolLogin() {
            isParentLogin = false;
            document.getElementById('parent-login-btn').classList.remove('bg-indigo-600', 'text-white');
            document.getElementById('parent-login-btn').classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            document.getElementById('school-login-btn').classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            document.getElementById('school-login-btn').classList.add('bg-indigo-600', 'text-white');
            
            document.getElementById('login-title').textContent = 'Sign In';
            document.getElementById('login-subtitle').textContent = 'Enter your credentials to access your account';
            document.getElementById('login-form').action = '{{ route("login") }}';
            document.getElementById('email').placeholder = 'student@school.edu';
            
            const demoAccounts = document.getElementById('demo-accounts');
            if (demoAccounts) {
                demoAccounts.innerHTML = `
                    <p class="text-xs font-semibold text-blue-900 dark:text-blue-100 mb-2">Demo Accounts:</p>
                    <div class="text-xs text-blue-800 dark:text-blue-200 space-y-1">
                        <p><strong>Admin:</strong> admin1@test.com / password</p>
                        <p><strong>Teacher:</strong> teacher1@test.com / password</p>
                        <p><strong>Student:</strong> student1@test.com / password</p>
                    </div>
                `;
            }
            
            // Update register link for school
            const registerLink = document.getElementById('register-link');
            if (registerLink) {
                registerLink.href = '{{ route("register") }}';
                registerLink.textContent = 'Create Account';
            }
        }
    </script>
</x-guest-layout>
