@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-4">
        <h1 class="text-2xl font-bold text-green-700">Histórico de Transações</h1>
        @if (session('info'))
            <div class="mt-4 p-4 bg-yellow-100 text-yellow-700 rounded">{{ session('info') }}</div>
        @endif
        @if ($card)
            <div class="mt-4">
                <h2 class="text-xl font-semibold">Operações</h2>
                @if ($transactions->isEmpty())
                    <p class="mt-2 text-gray-600">Nenhuma operação registrada.</p>
                @else
                    <table class="w-full border-collapse mt-2">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2 text-left">Tipo</th>
                                <th class="px-4 py-2 text-left">Valor</th>
                                <th class="px-4 py-2 text-left">Data</th>
                                <th class="px-4 py-2 text-left">Método</th>
                                <th class="px-4 py-2 text-left">Referência</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $transaction->type }}</td>
                                    <td class="px-4 py-2">€{{ number_format($transaction->value, 2) }}</td>
                                    <td class="px-4 py-2">{{ $transaction->date }}</td>
                                    <td class="px-4 py-2">{{ $transaction->payment_type ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $transaction->payment_reference ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <h2 class="text-xl font-semibold mt-4">Compras</h2>
                @if ($orders->isEmpty())
                    <p class="mt-2 text-gray-600">Nenhuma compra registrada.</p>
                @else
                    @foreach ($orders as $order)
                        <div class="mt-2 p-4 bg-white rounded-lg shadow">
                            <p><strong>Pedido #{{ $order->id }}</strong> - Total: €{{ number_format($order->total, 2) }} - Data: {{ $order->date }}</p>
                            @if ($order->pdf_receipt)
                                <a href="{{ asset('storage/receipts/' . $order->pdf_receipt) }}" target="_blank" class="text-blue-600">Ver Recibo</a>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            <p class="mt-4 text-gray-600">Nenhum cartão encontrado.</p>
        @endif
    </div>
@endsection
