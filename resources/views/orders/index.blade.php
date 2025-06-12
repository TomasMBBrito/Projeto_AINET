@extends('layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Orders Management</h1>

        @if(in_array(Auth::user()->type, ['employee', 'board']))
        <div class="flex space-x-2">
            <a href="{{ route('supply-orders.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Manage Supply Orders
            </a>
        </div>
        @endif
    </div>

    <!-- Status Filter -->
    <div class="mb-6 flex space-x-2">
        <a href="{{ route('orders.index') }}"
           class="{{ !request('status') ? 'bg-green-600 text-white' : 'bg-gray-200' }} px-4 py-2 rounded">
            All Orders
        </a>
        @foreach(['pending', 'completed', 'canceled'] as $status)
            <a href="{{ route('orders.index', ['status' => $status]) }}"
               class="{{ request('status') === $status ? 'bg-green-600 text-white' : 'bg-gray-200' }} px-4 py-2 rounded">
                {{ ucfirst($status) }}
            </a>
        @endforeach
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items_Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($order->member)->name ?? 'Membro apagado' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->total_items }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">€{{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'canceled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>

                        <!-- @can('complete', $order)
                        <form action="{{ route('orders.complete', $order->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-green-600 hover:text-green-900">Complete</button>
                        </form>
                        @endcan -->

                        <!-- @can('cancel', $order)
                        <button onclick="openCancelModal({{ $order->id }})" class="text-red-600 hover:text-red-900 ml-2">Cancel</button>
                        @endcan -->
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center">No orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
    {{ $orders->appends(request()->query())->links() }}
</div>
</div>

<!-- Cancel Order Modal
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium mb-4">Cancel Order</h3>
        <form id="cancelForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Reason</label>
                <textarea id="cancel_reason" name="cancel_reason" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeCancelModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium">Confirm Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCancelModal(orderId) {
        const form = document.getElementById('cancelForm');
        form.action = `/orders/${orderId}/cancel`;
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }
</script> -->
@endsection
