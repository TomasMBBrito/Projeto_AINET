@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-4">
        <h1 class="text-2xl font-bold text-green-700">Shopping cart</h1>

        @if (session('success'))
            <div id="toast" class="mt-4 p-4 bg-green-100 text-green-700 rounded flex items-center">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('toast').style.display='none'" class="ml-4 text-green-500">
                    ×
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if (empty($cartItems))
            <p class="mt-4 text-gray-600">Your cart is empty.</p>
            <a href="{{ route('catalog.index') }}"
                class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Back to Catalog
            </a>
        @else
            <div class="mt-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Produvt</th>
                            <th class="px-4 py-2 text-left">Unit Price</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                            <th class="px-4 py-2 text-left">Stock</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item['name'] }}</td>
                                <td class="px-4 py-2">
                                    @if ($item['price'] != $item['effective_price'])
                                        <span
                                            class="text-green-700">€{{ number_format($item['effective_price'], 2) }}</span>
                                        <span
                                            class="line-through text-gray-400 ml-2">€{{ number_format($item['price'], 2) }}</span>
                                    @else
                                        €{{ number_format($item['price'], 2) }}
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0"
                                            class="w-16 p-1 border rounded mr-2">
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-sm">
                                            Update
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-2">€{{ number_format($item['subtotal'], 2) }}</td>
                                <td class="px-4 py-2">
                                    @if ($item['low_stock'])
                                        <span class="text-red-500 font-semibold">Low stock: {{ $item['stock'] }} (
                                        Delivey may take time)</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Stock: {{ $item['stock'] }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <button type="submit"
                                            class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-sm">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-right">
                    <p class="text-sm">Subtotal: €{{ number_format($total, 2) }}</p>
                    <p class="text-sm">Shipping Cost: €{{ number_format($shippingCost, 2) }}</p>
                    <p class="text-lg font-bold">Total: €{{ number_format($total + $shippingCost, 2) }}</p>
                </div>

                <div class="mt-6 bg-white p-4 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nif" class="block text-gray-700 font-medium mb-1">NIF</label>
                                <input type="text" id="nif" name="nif" value="{{ old('nif', $nif ?? '') }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2" maxlength="9">
                                @error('nif')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="delivery_address" class="block text-gray-700 font-medium mb-1">
                                    Delivery Address
                                </label>
                                <textarea id="delivery_address" name="delivery_address" rows="2"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2" required>{{ old('delivery_address', $delivery_address ?? '') }}</textarea>
                                @error('delivery_address')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 text-right">
                            <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                Confirm Purchase
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-4 flex space-x-4">
                    <a href="{{ route('catalog.index') }}"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        Back to catalog
                    </a>

                    <form action="{{ route('cart.clear-cart') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                            Clean Cart
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
