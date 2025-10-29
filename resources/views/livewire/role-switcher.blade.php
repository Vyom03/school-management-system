<div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 p-4 mb-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                <strong>Development Mode:</strong> Switch roles to test different user experiences.
            </p>
            <div class="mt-3 flex flex-wrap gap-2">
                <span class="text-xs text-yellow-700 dark:text-yellow-200 mr-2">Current: <strong>{{ $currentRole }}</strong></span>
                @foreach ($roles as $role)
                    <button 
                        wire:click="switchRole('{{ $role->name }}')"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md 
                        {{ $currentRole === $role->name 
                            ? 'bg-yellow-600 text-white' 
                            : 'bg-white text-yellow-700 hover:bg-yellow-50' }}
                        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                    >
                        {{ ucfirst($role->name) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    
    @if (session()->has('message'))
        <div class="mt-3 text-sm text-yellow-700 dark:text-yellow-200">
            {{ session('message') }}
        </div>
    @endif
</div>

