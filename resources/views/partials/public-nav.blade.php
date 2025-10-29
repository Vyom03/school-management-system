<nav class="bg-white/90 backdrop-blur border-b border-gray-100 fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="/" class="text-xl font-semibold text-gray-900">Greenfield School</a>
            </div>
            <div class="hidden md:flex space-x-6">
                <a class="text-gray-700 hover:text-gray-900" href="/">Home</a>
                <a class="text-gray-700 hover:text-gray-900" href="{{ route('about') }}">About</a>
                <a class="text-gray-700 hover:text-gray-900" href="{{ route('academics') }}">Academics</a>
                <a class="text-gray-700 hover:text-gray-900" href="{{ route('admissions') }}">Admissions</a>
                <a class="text-gray-700 hover:text-gray-900" href="{{ route('contact') }}">Contact</a>
                @if (Route::has('login'))
                    @auth
                        <a class="text-gray-700 hover:text-gray-900" href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a class="text-gray-700 hover:text-gray-900" href="{{ route('login') }}">Log in</a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

