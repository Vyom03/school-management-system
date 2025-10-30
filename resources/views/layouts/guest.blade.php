<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <!-- Logo Container with Background -->
            <div class="mb-8">
                <div class="flex flex-col items-center">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
                        <x-application-logo class="w-20 h-20 fill-current text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <h1 class="mt-4 text-2xl font-bold text-gray-800 dark:text-gray-100">School Management System</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Welcome Back</p>
                </div>
            </div>

            <!-- Login Card -->
            <div class="w-full sm:max-w-md">
                <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="px-8 py-8">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-8 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-center text-gray-600 dark:text-gray-400">
                            &copy; {{ date('Y') }} School Management System. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
