@extends('layouts.app')

@section('title', 'Encomendas/Stock')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">
    <div class="max-w-5xl w-full shadow-xl rounded-2xl p-8 bg-white">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Encomendas/Stock</h2>
        <p class="text-gray-600 mb-6">Aqui você pode gerenciar encomendas e stock.</p>

        @if($orders->isEmpty())
            <p class="text-center text-gray-500">Não existem encomendas no momento.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-left text-gray-600">
                            <th class="px-4 py-2">#</th>
                            <!-- <th class="px-4 py-2">Utilizador</th> -->
                            <!-- <th class="px-4 py-2">Produto</th> -->
                            <th class="px-4 py-2">Quantidade</th>
                            <th class="px-4 py-2">Preço Total</th>
                            <th class="px-4 py-2">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="border-t border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $order->id }}</td>
                                <!-- <td class="px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td> -->
                                <!-- <td class="px-4 py-2">{{ $order->product->name ?? 'N/A' }}</td> -->
                                <td class="px-4 py-2">{{ $order->total_items }}</td>
                                <td class="px-4 py-2">€{{ number_format($order->total, 2) }}</td>
                                <td class="px-4 py-2">{{ $order->date->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <a href="{{ route('home') }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg mt-6">
            Voltar ao Dashboard
        </a>
    </div>
</div>
@endsection
