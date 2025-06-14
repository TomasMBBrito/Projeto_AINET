@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-green-700 mb-6">Transaction History</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-700 font-medium mb-4">Card Balance: <span class="text-green-700 font-semibold">€{{ number_format($card->balance, 2) }}</span></p>

        @if ($operations->isEmpty())
            <p class="text-gray-600">No transactions recorded.</p>
        @else
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 text-left">Data</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Details</th>
                        <th class="px-4 py-2 text-left">Value</th>
                        <th class="px-4 py-2 text-left">Action</th>
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
                                        Payment via {{ $operation->payment_type }} ({{ $operation->payment_reference }})
                                    @elseif ($operation->credit_type == 'order_cancellation')
                                        Order Cancellation #{{ $operation->order_id }}
                                    @endif
                                @else
                                    @if ($operation->debit_type == 'order')
                                        Order #{{ $operation->order_id }}
                                    @elseif ($operation->debit_type == 'membership_fee')
                                        Membership Fee
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
                                       class="text-blue-600 hover:underline">View Receipt</a>
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
                Back to Card Details
            </a>
        </div>
    </div>
</div>
@endsection
