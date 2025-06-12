@extends('layouts.app')

@section('title', 'Cancel Order')

@section('content')
@if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Cancel Order #{{ $order->id }}</h1>

    <form method="POST" action="{{ route('orders.cancel', $order->id) }}">
        @csrf
        <div class="mb-4">
            <label for="cancel_reason" class="block text-sm font-medium text-gray-700">
                Reason for cancellation:
            </label>
            <textarea name="cancel_reason" id="cancel_reason" rows="4" required
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            </textarea>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('orders.show', $order->id) }}"
               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700">
                Back
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-900">
                Confirm Cancellation
            </button>
        </div>
    </form>
</div>
@endsection
