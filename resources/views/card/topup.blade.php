@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Adicionar Saldo ao Cartão</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <p class="text-lg mb-4">Saldo atual: <strong>{{ number_format($balance, 2) }} €</strong></p>

    <form action="{{ route('card.topup') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="amount" class="block text-sm font-medium mb-1">Valor a adicionar (€)</label>
            <input type="number" name="amount" id="amount" step="0.01" min="1" required
                   class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            Adicionar Saldo
        </button>
    </form>
</div>
@endsection
