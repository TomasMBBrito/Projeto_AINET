@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-green-700 mb-6">Confirmar Compra</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <ul class="divide-y divide-gray-200 mb-6">
            @foreach($cart as $productId => $item)
                <li class="flex justify-between py-3">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500">Quantidade: {{ $item['quantity'] }}</p>
                    </div>
                    <span class="font-medium text-green-700">€{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                </li>
            @endforeach
            <li class="flex justify-between py-3 font-semibold">
                <span>Total de Artigos:</span>
                <span>{{ $totalItems }}</span>
            </li>
            <li class="flex justify-between py-3 font-semibold">
                <span>Portes de envio:</span>
                <span>€{{ number_format($shippingCost, 2) }}</span>
            </li>
            <li class="flex justify-between py-3 font-bold text-lg border-t border-gray-200">
                <span>Total:</span>
                <span class="text-green-700">€{{ number_format($total, 2) }}</span>
            </li>
        </ul>

        <form action="{{ route('cart.process') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="nif" class="block text-gray-700 font-medium mb-1">NIF <span class="text-red-500">*</span></label>
                <input type="text" id="nif" name="nif" required maxlength="9"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"
                       placeholder="Insira seu NIF">
                @error('nif')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="delivery_address" class="block text-gray-700 font-medium mb-1">Morada de Entrega <span class="text-red-500">*</span></label>
                <textarea id="delivery_address" name="delivery_address" rows="3" required
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"
                          placeholder="Endereço completo para entrega"></textarea>
                @error('delivery_address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded transition">
                Confirmar Compra
            </button>
        </form>
    </div>
</div>
@endsection
