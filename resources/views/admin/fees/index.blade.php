<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Fee Management') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.fees.structures') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                    Fee Structures
                </a>
                <button onclick="document.getElementById('assign-fee-modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    + Assign Fee
                </button>
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

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Fees</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_fees'] }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg shadow">
                    <p class="text-sm text-blue-600 dark:text-blue-400">Pending</p>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $stats['pending_fees'] }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg shadow">
                    <p class="text-sm text-green-600 dark:text-green-400">Paid</p>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $stats['paid_fees'] }}</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg shadow">
                    <p class="text-sm text-red-600 dark:text-red-400">Overdue</p>
                    <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ $stats['overdue_fees'] }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg shadow">
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Amount Due</p>
                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">${{ number_format($stats['total_amount_due'], 2) }}</p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg shadow">
                    <p class="text-sm text-purple-600 dark:text-purple-400">Collected</p>
                    <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">${{ number_format($stats['total_collected'], 2) }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search student..." class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    <select name="status" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ $status === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="waived" {{ $status === 'waived' ? 'selected' : '' }}>Waived</option>
                    </select>
                    <select name="student_id" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ $studentId == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Filter</button>
                </form>
            </div>

            <!-- Fees Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($fees->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fee Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Paid</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($fees as $fee)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $fee->student->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $fee->student->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $fee->feeStructure->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                ${{ number_format($fee->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $fee->due_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'partial' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                        'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                        'waived' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$fee->status] ?? 'bg-gray-100' }}">
                                                    {{ ucfirst($fee->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                ${{ number_format($fee->total_paid, 2) }} / ${{ number_format($fee->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.fees.show', $fee) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $fees->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No fees found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Fee Modal -->
    <div id="assign-fee-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Assign Fee</h3>
                <button onclick="document.getElementById('assign-fee-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <form action="{{ route('admin.fees.assign') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fee Structure</label>
                        <select name="fee_structure_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            <option value="">Select...</option>
                            @foreach($feeStructures as $structure)
                                <option value="{{ $structure->id }}">{{ $structure->name }} - ${{ number_format($structure->amount, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Students</label>
                        <select name="student_ids[]" multiple required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" style="height: 150px;">
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple students</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (optional)</label>
                        <input type="number" step="0.01" name="amount" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Leave empty for default">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                        <input type="date" name="due_date" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('assign-fee-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded-md">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Assign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

