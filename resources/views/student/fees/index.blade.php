<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Fees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Due</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($stats['total_due'], 2) }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg shadow">
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $stats['pending_count'] }}</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg shadow">
                    <p class="text-sm text-red-600 dark:text-red-400">Overdue</p>
                    <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ $stats['overdue_count'] }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg shadow">
                    <p class="text-sm text-green-600 dark:text-green-400">Paid</p>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $stats['paid_count'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
                <form method="GET" class="flex gap-4">
                    <select name="status" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ $status === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Filter</button>
                </form>
            </div>

            <!-- Fees List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($fees->count() > 0)
                        <div class="space-y-4">
                            @foreach($fees as $fee)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $fee->feeStructure->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $fee->feeStructure->description ?? '' }}
                                            </p>
                                            <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Amount:</span>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">${{ number_format($fee->amount, 2) }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Due Date:</span>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">{{ $fee->due_date->format('M d, Y') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Paid:</span>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">${{ number_format($fee->total_paid, 2) }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Remaining:</span>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">${{ number_format($fee->remaining_amount, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(100, $fee->payment_percentage) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex flex-col items-end">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                    'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                    'partial' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                    'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                    'waived' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$fee->status] ?? 'bg-gray-100' }} mb-2">
                                                {{ ucfirst($fee->status) }}
                                            </span>
                                            <a href="{{ route('student.fees.show', $fee) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 text-sm">View Details →</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No fees found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

