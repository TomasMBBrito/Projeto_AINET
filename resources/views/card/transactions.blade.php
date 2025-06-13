@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-green-700 mb-6">Histórico de Transações</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-700 font-medium mb-4">Saldo do Cartão: <span class="text-green-700 font-semibold">€{{ number_format($card->balance, 2) }}</span></p>

        @if ($operations->isEmpty())
            <p class="text-gray-600">Nenhuma transação registada.</p>
        @else
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 text-left">Data</th>
                        <th class="px-4 py-2 text-left">Tipo</th>
                        <th class="px-4 py-2 text-left">Detalhes</th>
                        <th class="px-4 py-2 text-left">Valor</th>
                        <th class="px-4 py-2 text-left">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operations as $operation)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $operation->date }}</td>
                            <td class="px-4 py-2">
                                {{ $operation->type == 'credit' ? 'Crédito' : 'Débito' }}
                            </td>
                            <td class="px-4 py-2">
                                @if ($operation->type == 'credit')
                                    @if ($operation->credit_type == 'payment')
                                        Pagamento via {{ $operation->payment_type }} ({{ $operation->payment_reference }})
                                    @elseif ($operation->credit_type == 'order_cancellation')
                                        Cancelamento da Encomenda #{{ $operation->order_id }}
                                    @endif
                                @else
                                    @if ($operation->debit_type == 'order')
                                        Encomenda #{{ $operation->order_id }}
                                    @elseif ($operation->debit_type == 'membership_fee')
                                        Quota de Membro
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <span class="{{ $operation->type == 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $operation->type == 'credit' ? '+' : '-' }}€{{ number_format($operation->value, 2) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if ($operation->order_id && $operation->order && $operation->order->pdf_receipt)
                                    <a href="{{ route('orders.invoice', $operation->order_id) }}"
                                       class="text-blue-600 hover:underline">Ver Recibo</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="mt-6">
            <a href="{{ route('card.index') }}"
               class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                Voltar ao Cartão
            </a>
        </div>
    </div>
</div>
@endsection
