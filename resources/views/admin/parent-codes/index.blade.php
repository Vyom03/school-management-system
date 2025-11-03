<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Parent Registration Codes') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.parent-codes.bulk-create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    📄 Bulk Generate (PDF)
                </a>
                <a href="{{ route('admin.parent-codes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    + Generate Code
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('generated_codes'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    <p class="font-semibold mb-2">Generated Codes:</p>
                    <div class="space-y-1">
                        @foreach(session('generated_codes') as $code)
                            <p><strong>{{ $code->code }}</strong> - For {{ $code->student->name }} (Expires: {{ $code->expires_at->format('M d, Y') }})</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($codes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Code</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Student</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Relationship</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Expires</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Created By</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($codes as $code)
                                        <tr>
                                            <td class="px-4 py-3 text-sm font-mono font-bold text-gray-900 dark:text-gray-100">
                                                {{ $code->code }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $code->student->name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $code->email ?? 'Any' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                {{ ucfirst($code->relationship) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if($code->used)
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Used
                                                    </span>
                                                @elseif($code->expires_at && $code->expires_at->isPast())
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Expired
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Active
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $code->expires_at ? $code->expires_at->format('M d, Y') : 'Never' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $code->creator->name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if(!$code->used)
                                                    <form method="POST" action="{{ route('admin.parent-codes.destroy', $code->id) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-500" onclick="return confirm('Are you sure you want to delete this code?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $codes->links() }}
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No registration codes generated yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

