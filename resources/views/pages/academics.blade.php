@extends('layouts.public')

@section('title', 'Academics • Greenfield School')
@section('content')
            <div class="max-w-5xl mx-auto px-6 py-12">
                <h1 class="text-3xl font-bold text-gray-900">Academics</h1>
                <p class="mt-4 text-gray-700">Our academic program emphasizes critical thinking, collaboration, and creativity across all grade levels.</p>
                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm"><h3 class="font-semibold text-gray-900">Lower School</h3><p class="mt-2 text-gray-600">Foundational literacy, numeracy, and inquiry-based learning.</p></div>
                    <div class="bg-white p-6 rounded-xl shadow-sm"><h3 class="font-semibold text-gray-900">Middle School</h3><p class="mt-2 text-gray-600">Interdisciplinary projects and expanding independence.</p></div>
                    <div class="bg-white p-6 rounded-xl shadow-sm"><h3 class="font-semibold text-gray-900">Upper School</h3><p class="mt-2 text-gray-600">Advanced courses, AP options, and college counseling.</p></div>
                </div>
            </div>
@endsection

