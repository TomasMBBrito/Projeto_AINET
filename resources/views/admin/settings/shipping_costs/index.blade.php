<!-- admin/settings/shipping_costs/index.blade.php - Conteúdo completo gerado com base no projeto -->
@extends('layouts.app')

@section('content')
<div class="px-6"> <!-- Adicionado espaçamento lateral -->

    <h2 class="text-3xl font-bold mb-6">🚚 Gestão de Custos de Envio</h2>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full text-left border-collapse">
            <thead class="bg-green-100">
                <tr>
                    <th class="px-4 py-2">Valor Mínimo (€)</th>
                    <th class="px-4 py-2">Valor Máximo (€)</th>
                    <th class="px-4 py-2">Custo de Envio (€)</th>
                    <th class="px-4 py-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shippingCosts as $cost)
                    <tr>
                        <td class="p-2 border">{{ $cost->min_value_threshold }}</td>
                        <td class="p-2 border">{{ $cost->max_value_threshold }}</td>
                        <td class="p-2 border">
                            {{ $cost->shipping_cost == 0 ? 'Grátis' : number_format($cost->shipping_cost, 2, ',', '.') . ' €' }}
                        </td>
                        <td class="p-2 border">
                            <a href="{{ route('admin.settings.shipping_costs.edit', $cost) }}" class="text-blue-600 hover:underline">Editar</a>
                            <form action="{{ route('admin.settings.shipping_costs.destroy', $cost) }}" method="POST" class="inline-block ml-2">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('admin.settings.shipping_costs.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            ➕ Novo Custo de Envio
        </a>
        {{ $shippingCosts->links() }}
    </div>

</div> <!-- Fecho do espaçamento -->
@endsection
