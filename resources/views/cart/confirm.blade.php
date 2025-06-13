@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-green-700 mb-6">Confirmar Compra</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <ul class="divide-y divide-gray-200 mb-6">
            @foreach($orderData['cartItems'] as $item)
                <li class="flex justify-between py-3">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500">Quantidade: {{ $item['quantity'] }}</p>
                    </div>
                    <span class="font-medium text-green-700">€{{ number_format($item['subtotal'], 2) }}</span>
                </li>
            @endforeach
            <li class="flex justify-between py-3 font-semibold">
                <span>Total de Artigos:</span>
                <span>€{{ number_format($orderData['totalItems'], 2) }}</span>
            </li>
            <li class="flex justify-between py-3 font-semibold">
                <span>Portes de envio:</span>
                <span>€{{ number_format($orderData['shippingCost'], 2) }}</span>
            </li>
            <li class="flex justify-between py-3 font-bold text-lg border-t border-gray-200">
                <span>Total:</span>
                <span class="text-green-700">€{{ number_format($orderData['total'], 2) }}</span>
            </li>
        </ul>

        <form action="{{ route('cart.finalize') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="nif" class="block text-gray-700 font-medium mb-1">NIF <span class="text-red-500">*</span></label>
                <input type="text" id="nif" name="nif" value="{{ $orderData['nif'] }}" maxlength="9"
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
                          placeholder="Endereço completo para entrega">{{ $orderData['delivery_address'] }}</textarea>
                @error('delivery_address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="payment_method" class="block text-gray-700 font-medium mb-1">Método de Pagamento</label>
                <select name="payment_method" id="payment_method" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="visa">Visa</option>
                    <option value="paypal">PayPal</option>
                    <option value="mbway">MB WAY</option>
                </select>
            </div>
            <div id="payment_details" class="mb-4">
                <div class="visa-details hidden">
                    <label for="card_number" class="block text-gray-700 font-medium mb-1">Número do Cartão (16 dígitos)</label>
                    <input type="text" name="card_number" id="card_number" maxlength="16" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <label for="cvc_code" class="block text-gray-700 font-medium mb-1 mt-2">CVC (3 dígitos)</label>
                    <input type="text" name="cvc_code" id="cvc_code" maxlength="3" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="paypal-details hidden">
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="mbway-details hidden">
                    <label for="phone_number" class="block text-gray-700 font-medium mb-1">Número de Telefone (9 dígitos, começa com 9)</label>
                    <input type="text" name="phone_number" id="phone_number" maxlength="9" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded transition">
                Confirmar e Pagar
            </button>
            <a href="{{ route('cart.index') }}"
               class="mt-4 w-full inline-block text-center bg-gray-700 hover:bg-gray-800 text-white font-semibold py-3 rounded transition">
                Cancelar
            </a>
        </form>
        <script>
            document.getElementById('payment_method').addEventListener('change', function() {
                const paymentDetails = document.getElementById('payment_details');
                paymentDetails.querySelectorAll('.visa-details, .paypal-details, .mbway-details').forEach(div => div.classList.add('hidden'));
                paymentDetails.querySelector(`.${this.value}-details`).classList.remove('hidden');
            });
            document.getElementById('payment_method').dispatchEvent(new Event('change'));
        </script>
    </div>
</div>
@endsection
