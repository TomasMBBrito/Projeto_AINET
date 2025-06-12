@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-xl">
    <h1 class="text-2xl font-bold text-green-700 mb-6">Top Up Virtual Card</h1>

    <div class="bg-white shadow rounded p-6 mb-6">
        <p class="text-gray-800 font-semibold mb-1">Card Number:</p>
        <p class="text-gray-600 mb-4">*** {{ substr($card->card_number, -3) }}</p>

        <p class="text-gray-800 font-semibold mb-1">Actual Balance:</p>
        <p class="text-gray-600 mb-4">{{ $card->balance }}</p>

        <!-- <p class="text-gray-800 font-semibold mb-1">Actual Balance:</p>
        <p class="text-gray-600">{{ $card->name }}</p> -->
    </div>

    <form action="{{ route('card.topup.store', $card->id) }}" method="POST" class="bg-white shadow rounded p-6">
        @csrf
        <div class="mb-4">
            <label for="amount" class="block text-gray-700 font-medium mb-2">Amount to Add (€)</label>
            <input type="number" name="amount" id="amount" min="1" step="0.01"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500"
                required>
        </div>

        <button type="submit"
            class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition font-semibold">
            Confirm Top Up
        </button>
    </form>
</div>
@endsection
