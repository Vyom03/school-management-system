@extends('layouts.public')

@section('title', 'Contact • Greenfield School')
@section('content')
            <div class="max-w-4xl mx-auto px-6 py-12">
                <h1 class="text-3xl font-bold text-gray-900">Contact Us</h1>
                <p class="mt-4 text-gray-700">Have questions? We’d love to hear from you. Send us a message and we’ll respond soon.</p>
                <div class="mt-8">
                    <livewire:contact-form />
                </div>
            </div>
@endsection

