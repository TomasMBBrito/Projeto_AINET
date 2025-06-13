@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-green-700 mb-6">Creditar Cartão</h2>

    @if (session('error'))
        <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('card.credit') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="amount" class="block text-gray-700 font-medium mb-1">Montante (€) <span class="text-red-500">*</span></label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"
                       placeholder="Insira o montante">
                @error('amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="payment_type" class="block text-gray-700 font-medium mb-1">Método de Pagamento <span class="text-red-500">*</span></label>
                <select id="payment_type" name="payment_type" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"
                        onchange="updatePaymentFields()">
                    <option value="" disabled {{ !$default_payment_type ? 'selected' : '' }}>Selecione um método</option>
                    <option value="Visa" {{ $default_payment_type == 'Visa' ? 'selected' : '' }}>Visa</option>
                    <option value="PayPal" {{ $default_payment_type == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                    <option value="MB WAY" {{ $default_payment_type == 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                </select>
                @error('payment_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="payment_reference_field">
                <label for="payment_reference" class="block text-gray-700 font-medium mb-1">Referência de Pagamento <span class="text-red-500">*</span></label>
                <input type="text" id="payment_reference" name="payment_reference" required
                       value="{{ $default_payment_reference ?? '' }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"
                       placeholder="Insira a referência">
                @error('payment_reference')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="cvc_code_field" class="hidden">
                <label for="cvc_code" class="block text-gray-700 font-medium mb-1">CVC <span class="text-red-500">*</span></label>
                <input type="text" id="cvc_code" name="cvc_code"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600"
                       placeholder="Insira o código CVC">
                @error('cvc_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded transition">
                Creditar
            </button>
            <a href="{{ route('card.index') }}"
               class="mt-4 w-full inline-block text-center bg-gray-700 hover:bg-gray-800 text-white font-semibold py-3 rounded transition">
                Cancelar
            </a>
        </form>
    </div>

    <script>
        function updatePaymentFields() {
            const paymentType = document.getElementById('payment_type').value;
            const paymentReference = document.getElementById('payment_reference');
            const cvcCodeField = document.getElementById('cvc_code_field');

            if (paymentType === 'Visa') {
                cvcCodeField.classList.remove('hidden');
                paymentReference.placeholder = 'Insira o número do cartão (16 dígitos)';
            } else {
                cvcCodeField.classList.add('hidden');
                if (paymentType === 'PayPal') {
                    paymentReference.placeholder = 'Insira o email do PayPal';
                } else if (paymentType === 'MB WAY') {
                    paymentReference.placeholder = 'Insira o número de telemóvel (9 dígitos, começa com 9)';
                } else {
                    paymentReference.placeholder = 'Insira a referência';
                }
            }
        }

        // Initialize on page load
        updatePaymentFields();
    </script>
</div>
@endsection
