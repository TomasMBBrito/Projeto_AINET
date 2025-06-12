@extends('layouts.app')

@section('title', 'Order Details')

@section('content')

@if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Order #{{ $order->id }} Details</h1>
        <a href="{{ route('orders.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            Back to Orders
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Date:</strong> {{ $order->date->format('d/m/Y') }}</div>
            <div><strong>Status:</strong> 
                <span class="px-2 py-1 text-sm rounded-full 
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status === 'canceled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div><strong>Total Items:</strong> {{ $order->total_items }}</div>
            <!-- <div><strong>Total:</strong> €{{ number_format($order->total, 2) }}</div> -->
            <div><strong>NIF:</strong> {{ $order->nif }}</div>
            <div><strong>Delivery Address:</strong> {{ $order->delivery_address }}</div>
        </div>

        @if($order->cancel_reason)
            <div class="mt-4 text-red-600">
                <strong>Cancel Reason:</strong> {{ $order->cancel_reason }}
            </div>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount Per Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($order->products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">€{{ number_format($product->order_item->unit_price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->order_item->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->order_item->discount }} €</td>
                    <td class="px-6 py-4 whitespace-nowrap">€{{ number_format($product->order_item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-right">
        <p class="text-lg">
            <strong>Total Shipping Cost:</strong>
            €{{ number_format($order->products->sum(fn($product) => $product->order_item->shipping_cost), 2) }}
        </p>
        <p class="text-lg">
            <strong>Order Total (with Shipping):</strong>
            €{{ number_format($order->total + $order->products->sum(fn($product) => $product->order_item->shipping_cost), 2) }}
        </p>
    </div>

     @if($order->status === 'completed')
            <a href="{{ route('orders.invoice', $order->id) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white mt-4 mr-4 px-4 py-2 rounded">
                Download Receipt (PDF)
            </a>
    @endif

    <div class="mt-6 flex justify-end space-x-3">
        
            @if(in_array(Auth::user()->type, ['employee', 'board']) && $order->status === 'pending')
                <form action="{{ route('orders.complete', $order->id) }}" method="POST">
                    @csrf
                    <!-- @method('PATCH') -->
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Complete Order
                    </button>
                </form>

                <form action="{{ route('orders.cancel.form', $order->id) }}" method="GET">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-900 ml-2">
                        Cancel
                    </button>
                </form> 

            <!-- Modal específico para este order
            <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
                    <h3 class="text-lg font-medium mb-4">Cancel Order #{{ $order->id }}</h3>
                    <form id="cancelForm" action="{{ route('orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <textarea id="cancel_reason" name="cancel_reason" rows="3" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="closeCancelModal()"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium">
                                Confirm Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>                 -->
            @endif      
    </div>
</div>

<!-- <script>
    function openCancelModal(orderId) {
        const modal = document.getElementById(`cancelModal`);
        modal.classList.remove('hidden');
    }

    function closeCancelModal() {
        const modal = document.getElementById(`cancelModal`);
        modal.classList.add('hidden');
    }
</script> -->
@endsection
