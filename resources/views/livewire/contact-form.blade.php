<div class="bg-white p-6 rounded-xl shadow-sm">
    @if ($sent)
        <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">
            Thank you! Your message has been sent.
        </div>
    @endif

    <form wire:submit.prevent="submit" class="grid grid-cols-1 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" wire:model.lazy="name" class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Your name" />
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" wire:model.lazy="email" class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="you@example.com" />
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Message</label>
            <textarea rows="5" wire:model.lazy="message" class="mt-1 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="How can we help?"></textarea>
            @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-md">Send Message</button>
        </div>
    </form>
</div>

