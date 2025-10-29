@extends('layouts.public')

@section('title', 'Greenfield School')
@section('meta_description', 'A vibrant K-12 community dedicated to academic excellence, creativity, and character.')

@section('content')
        <!-- Hero -->
        <section>
            <div class="relative overflow-hidden">
                <div class="absolute inset-0">
                    <img src="{{ asset('images/hero.svg') }}" width="1600" height="900" alt="School campus" class="w-full h-full object-cover opacity-80" fetchpriority="high" loading="eager" decoding="async">
                </div>
                <div class="relative bg-black/50">
                    <div class="max-w-7xl mx-auto px-6 py-24">
                        <h1 class="text-4xl md:text-6xl font-extrabold text-white">Inspiring Minds. Shaping Futures.</h1>
                        <p class="mt-6 text-lg md:text-xl text-gray-100 max-w-2xl">A vibrant K-12 community dedicated to academic excellence, creativity, and character.</p>
                        <div class="mt-8 flex gap-4">
                            <a href="{{ route('admissions') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-md">Apply Now</a>
                            <a href="{{ route('about') }}" class="inline-block bg-white/90 hover:bg-white text-gray-900 font-semibold px-6 py-3 rounded-md">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick highlights -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-sm p-8">
                    <h3 class="text-xl font-semibold text-gray-900">Academic Excellence</h3>
                    <p class="mt-3 text-gray-600">Rigorous curriculum, passionate teachers, and personalized support to help every student thrive.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-8">
                    <h3 class="text-xl font-semibold text-gray-900">Arts & Athletics</h3>
                    <p class="mt-3 text-gray-600">From orchestra to robotics and varsity sports, we offer opportunities for every interest.</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-8">
                    <h3 class="text-xl font-semibold text-gray-900">Safe, Caring Community</h3>
                    <p class="mt-3 text-gray-600">Inclusive culture with strong partnerships between families, faculty, and students.</p>
                </div>
            </div>
        </section>

        <!-- Academics section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <img src="{{ asset('images/academics.svg') }}" width="1200" height="800" class="rounded-xl shadow-md" alt="Students in classroom" loading="lazy" decoding="async">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Challenging, Supportive Academics</h2>
                    <p class="mt-4 text-gray-600">Our teachers design engaging, standards-aligned lessons and provide targeted feedback to support deep understanding and mastery.</p>
                    <a href="{{ route('academics') }}" class="mt-6 inline-block text-blue-700 hover:text-blue-800 font-semibold">Explore Academics →</a>
                        </div>
                    </div>
        </section>

        <!-- Gallery -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-gray-900">Life at Greenfield</h2>
                <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <img class="h-40 w-full object-cover rounded-lg" alt="Campus" src="{{ asset('images/gallery-1.svg') }}" width="800" height="600" loading="lazy" decoding="async">
                    <img class="h-40 w-full object-cover rounded-lg" alt="Library" src="{{ asset('images/gallery-2.svg') }}" width="800" height="600" loading="lazy" decoding="async">
                    <img class="h-40 w-full object-cover rounded-lg" alt="Science lab" src="{{ asset('images/gallery-3.svg') }}" width="800" height="600" loading="lazy" decoding="async">
                    <img class="h-40 w-full object-cover rounded-lg" alt="Sports" src="{{ asset('images/gallery-4.svg') }}" width="800" height="600" loading="lazy" decoding="async">
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-16 bg-blue-600">
            <div class="max-w-7xl mx-auto px-6 text-white">
                <div class="md:flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold">Schedule a Campus Tour</h2>
                        <p class="mt-2 opacity-90">See our classrooms, meet our teachers, and experience our community.</p>
                    </div>
                    <a href="{{ route('contact') }}" class="mt-6 md:mt-0 inline-block bg-white text-blue-700 font-semibold px-6 py-3 rounded-md">Contact Us</a>
                </div>
            </div>
        </section>

        <footer class="py-8 border-t bg-white">
            <div class="max-w-7xl mx-auto px-6 text-sm text-gray-600 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>© {{ date('Y') }} Greenfield School. All rights reserved.</div>
                <div class="mt-2 md:mt-0 space-x-4">
                    <a class="hover:text-gray-900" href="{{ route('about') }}">About</a>
                    <a class="hover:text-gray-900" href="{{ route('academics') }}">Academics</a>
                    <a class="hover:text-gray-900" href="{{ route('admissions') }}">Admissions</a>
                    <a class="hover:text-gray-900" href="{{ route('contact') }}">Contact</a>
            </div>
        </div>
        </footer>
@endsection
