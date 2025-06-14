@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8 px-4"> <!-- padding horizontal adicionado -->
    <h2 class="text-xl font-bold mb-4">Edit Shipping Cost</h2>

    <form action="{{ route('admin.settings.shipping_costs.update', $shippingCost) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium">Minimum value (€)</label>
            <input type="number" step="0.01" name="min_value_threshold" class="w-full p-2 border rounded" value="{{ $shippingCost->min_value_threshold }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Maximum value (€)</label>
            <input type="number" step="0.01" name="max_value_threshold" class="w-full p-2 border rounded" value="{{ $shippingCost->max_value_threshold }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Shipping Cost (€)</label>
            <input type="number" step="0.01" name="shipping_cost" class="w-full p-2 border rounded" value="{{ $shippingCost->shipping_cost }}" required>
        </div>

        <div class="flex flex-wrap gap-4 mt-6">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Update
            </button>
            <a href="{{ route('admin.settings.shipping_costs.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Back to list
            </a>
        </div>
    </form>
</div>
@endsection
