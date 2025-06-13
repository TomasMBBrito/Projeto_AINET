@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-green-700 mb-6">Detalhes do Cartão</h2>

    @if (session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded flex items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="ml-4 text-green-500">×</button>
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-700 font-medium">Titular:</p>
                <p class="text-gray-800">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-medium">Número do Cartão:</p>
                <p class="text-gray-800">{{ $card->card_number }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-medium">Saldo Disponível:</p>
                <p class="text-green-700 font-semibold">€{{ number_format($card->balance, 2) }}</p>
            </div>
        </div>

        <div class="mt-6 flex space-x-4">
            <a href="{{ route('card.credit') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Creditar Cartão
            </a>
            <a href="{{ route('card.transactions') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Histórico de Transações
            </a>
        </div>
    </div>
</div>
@endsection
