@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h2 class="text-xl font-bold mb-4">New Shipping Cost</h2>

    <form action="{{ route('admin.settings.shipping_costs.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-medium">Minimum value (€)</label>
            <input type="number" step="0.01" name="min_value_threshold" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Maximum value (€)</label>
            <input type="number" step="0.01" name="max_value_threshold" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Shipping Cost (€)</label>
            <input type="number" step="0.01" name="shipping_cost" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Save
        </button>

        <a href="{{ route('admin.settings.shipping_costs.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Back to list
        </a>

    </form>
</div>
@endsection
