@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-4">
        <h1 class="text-2xl font-bold text-green-700">Carrinho de Compras</h1>

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
            <p class="mt-4 text-gray-600">O teu carrinho está vazio.</p>
            <a href="{{ route('catalog.index') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Voltar ao Catálogo
            </a>
        @else
            <div class="mt-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Produto</th>
                            <th class="px-4 py-2 text-left">Preço Unitário</th>
                            <th class="px-4 py-2 text-left">Quantidade</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                            <th class="px-4 py-2 text-left">Stock</th>
                            <th class="px-4 py-2 text-left">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item['name'] }}</td>
                                <td class="px-4 py-2">
                                    @if ($item['price'] != $item['effective_price'])
                                        <span class="text-green-700">€{{ number_format($item['effective_price'], 2) }}</span>
                                        <span class="line-through text-gray-400 ml-2">€{{ number_format($item['price'], 2) }}</span>
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
                                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-sm">
                                            Atualizar
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-2">€{{ number_format($item['subtotal'], 2) }}</td>
                                <td class="px-4 py-2">
                                    @if ($item['low_stock'])
                                        <span class="text-red-500 font-semibold">Stock baixo: {{ $item['stock'] }} (entrega pode demorar)</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Stock: {{ $item['stock'] }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                        <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-sm">
                                            Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 text-right">
                    <p class="text-sm">Subtotal: €{{ number_format($total, 2) }}</p>
                    <p class="text-sm">Custo de Envio: €{{ number_format($shippingCost, 2) }}</p>
                    <p class="text-lg font-bold">Total: €{{ number_format($total + $shippingCost, 2) }}</p>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div>
                        <a href="{{ route('catalog.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition mr-4">
                            Voltar ao Catálogo
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                                Limpar Carrinho
                            </button>
                        </form>
                    </div>
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            Finalizar Compra
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
