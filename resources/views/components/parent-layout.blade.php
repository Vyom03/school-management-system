@props(['header'])

<x-parent-layout>
    <x-slot name="header">
        {{ $header }}
    </x-slot>

    {{ $slot }}
</x-parent-layout>

