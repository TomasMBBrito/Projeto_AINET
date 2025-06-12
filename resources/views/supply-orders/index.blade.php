@extends('layouts.app')

@section('title', 'Supply Orders Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Supply Orders Management</h1>

    <!-- Status Filter -->
    <div class="mb-6 flex space-x-2">
        <a href="{{ route('supply-orders.index') }}"
           class="{{ !request('status') ? 'bg-green-600 text-white' : 'bg-gray-200' }} hover:bg-green-500 hover:text-white px-4 py-2 rounded">
            All Orders
        </a>
        @foreach(['requested', 'completed'] as $status)
            <a href="{{ route('supply-orders.index', ['status' => $status]) }}"
               class="{{ request('status') === $status ? 'bg-green-600 text-white' : 'bg-gray-200' }} hover:bg-green-500 hover:text-white px-4 py-2 rounded">
                {{ ucfirst($status) }}
            </a>
        @endforeach
        <a href="{{ route('supply-orders.index', ['low_stock' => 1]) }}"
           class="{{ request('low_stock') ? 'bg-red-600 text-white' : 'bg-gray-200' }} hover:bg-red-500 hover:text-white px-4 py-2 rounded">
            Low Stock Products
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Products List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium">Products</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($products as $product)
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h3 class="font-medium">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600">
                            Stock: {{ $product->stock }}
                            (Lower: {{ $product->stock_lower_limit }},
                            Upper: {{ $product->stock_upper_limit }})
                        </p>
                    </div>
                    <form action="{{ route('supply-orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex space-x-2">
                            <input type="number" name="quantity" min="1"
                                   value="{{ max(1, $product->stock_upper_limit - $product->stock) }}"
                                   class="w-20 border rounded px-2 py-1">
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                Order
                            </button>
                            <button type="submit" name="auto_calculate" value="1"
                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                                Auto
                            </button>
                        </div>
                    </form>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Supply Orders List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium">Supply Orders</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($supplyOrders as $order)
                <div class="px-6 py-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-medium">{{ $order->product->name }}</h3>
                            <p class="text-sm text-gray-600">
                                Quantity: {{ $order->quantity }} |
                                Created by: {{ $order->registeredBy->name }} |
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $order->status === 'requested' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="mt-2 flex space-x-2">
                        @if($order->status === 'requested')
                        <form action="{{ route('supply-orders.complete', $order->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-green-600 hover:text-green-900 text-sm">Complete</button>
                        </form>
                        <form action="{{ route('supply-orders.destroy', $order->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-6 py-4 text-center text-gray-500">
                    No supply orders found
                </div>
                @endforelse
            </div>
            <div class="px-6 py-4">
                {{ $supplyOrders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
