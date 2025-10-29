<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Greenfield School'))</title>
        <meta name="description" content="@yield('meta_description', 'Greenfield School: Inspiring Minds. Shaping Futures.')">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50">
        @include('partials.public-nav')
        <main class="pt-24">
            @yield('content')
        </main>
        @stack('scripts')
        @livewireScripts
    </body>
    </html>


