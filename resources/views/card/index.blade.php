@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-green-700 mb-6">My Virtual Cards</h1>

    @if($cards->isEmpty())
        <p class="text-gray-600">You have no virtual cards yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse bg-white shadow rounded">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-4 py-3">Card Number</th>
                        <th class="px-4 py-3">Balance</th>
                        <!-- <th class="px-4 py-3">Cardholder</th> -->
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cards as $card)
                        <tr class="hover:bg-green-50 transition">
                            <td class="px-4 py-3">*** {{ substr($card->card_number, -3) }}</td>
                            <td class="px-4 py-3">{{ $card->balance }}</td>
                            <!-- <td class="px-4 py-3">{{ $card->expiration_month }}/{{ $card->expiration_year }}</td> -->
                            <!-- <td class="px-4 py-3">{{ $card->name }}</td> -->
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('card.topup', $card->id) }}" class="inline-flex items-center text-green-700 hover:text-green-900 font-semibold transition">
                                    <i data-lucide="arrow-up-circle" class="w-5 h-5 mr-1"></i> Add Money
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('card.create') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            <i data-lucide="plus"></i> Add New Card
        </a>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
