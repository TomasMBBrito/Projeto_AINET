@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h2 class="text-xl font-bold mb-4">Novo Custo de Envio</h2>

    <form action="{{ route('admin.settings.shipping_costs.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-medium">Valor mínimo (€)</label>
            <input type="number" step="0.01" name="min_value_threshold" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Valor máximo (€)</label>
            <input type="number" step="0.01" name="max_value_threshold" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Custo de envio (€)</label>
            <input type="number" step="0.01" name="shipping_cost" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Guardar
        </button>

        <a href="{{ route('admin.settings.shipping_costs.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Voltar à lista
        </a>

    </form>
</div>
@endsection
