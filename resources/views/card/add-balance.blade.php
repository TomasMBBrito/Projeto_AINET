@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <h1 class="text-2xl font-bold text-green-700">Adicionar Saldo</h1>
        @if (session('error'))
            <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="mt-4 p-4 bg-yellow-100 text-yellow-700 rounded">{{ session('info') }}</div>
        @endif
        <form action="{{ route('card.process_payment') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 font-medium mb-1">Montante (€)</label>
                <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2">
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
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Adicionar Saldo</button>
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
@endsection
